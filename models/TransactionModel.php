<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransactionModel extends CI_Model
{
    public function step()
    {
        $data = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->row_object();
        if ($data) {
            return $data->step;
        } else {
            return 0;
        }
    }

    public function setting()
    {
        $check = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->num_rows();
        if ($check > 0) {
            return 'OPENED';
        } else {
            return 'CLOSED';
        }
    }

    public function loadData()
    {
        $shift = $this->getShift();
        if ($shift == 'NIGHT') {
            $shift = 'AFTERNOON';
        } else {
            $shift;
        }

        $now = $this->db->get_where('package_transaction', [
            'DATE(created_at)' => date('Y-m-d'), 'status' => $shift
        ])->num_rows();

        $this->db->select('id')->from('package_transaction')->where('DATE(created_at)', date('Y-m-d'));
        $all = $this->db->where_in('type', ['BREAKFAST', 'DPU'])->get()->num_rows();

        return [$now, $all];
    }

    public function checkNIS()
    {
        $nis = str_replace('_', '', $this->input->post('nis', true));
        $step = $this->input->post('step', true);
        $period = $this->dm->getperiod();

        //CHECK CARD
        $checkCard = $this->db->get_where('cards', ['id' => $nis])->row_object();
        if (!$checkCard) {
            return [
                'status' => 500,
                'message' => 'Kartu tidak valid'
            ];
        }

        $statusCard = $checkCard->status;
        if ($statusCard != 'ACTIVE') {
            $statusText = ['INACTIVE' => 'belum diaktivasi', 'BLOCKED' => 'sudah diblokir'];
            return [
                'status' => 500,
                'message' => 'Kartu ini ' . $statusText[$statusCard]
            ];
        }

        $nis = $checkCard->student_id;

        $cekStudent = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->num_rows();
        if ($cekStudent <= 0) {
            return [
                'status' => 500,
                'message' => 'Santri tidak ditemukan'
            ];
        }

        $checkPackage = $this->db->get_where('packages', [
            'student_id' => $nis, 'period' => $period,
            'step' => $step, 'status' => 'ACTIVE'
        ])->row_object();
        if (!$checkPackage) {
            return [
                'status' => 500,
                'message' => 'Santri ini tidak punya paket aktif pada tahap saat ini'
            ];
        }

        $packageID = $checkPackage->id;
        $package = $checkPackage->package;
        $transport = $checkPackage->transport;

        //TRANSPORT
        if ($transport <= 0) {
            $teksTransport = '';
        } else {
            $teksTransport = ' + Transport';
        }

        $shift = $this->getShift();
        if (!$shift || $shift == '') {
            return [
                'status' => 400,
                'message' => 'Saat ini jam tutup',
                'nis' => $nis,
                'text' => 'Paket ' . $package . $teksTransport
            ];
        }

        if ($shift == 'NIGHT') {
            $shift = 'AFTERNOON';
        } else {
            $shift;
        }

        if ($shift == 'BREAKFAST') {
            $type = 'BREAKFAST';
        } else {
            $type = 'DPU';
        }

        $checkTransaction = $this->db->select('id')->from('package_transaction')->where([
            'package_id' => $packageID, 'DATE(created_at)' => date('Y-m-d'), 'status' => $shift
        ])->get()->num_rows();

        $now = date('Y-m-d H:i:s');
        //BREAKFAST
        if ($shift == 'BREAKFAST') {
            if ($package === 'A' || $package === 'C') {
                return [
                    'status' => 400,
                    'message' => 'Transaksi sarapan hanya khusus Paket B dan D',
                    'nis' => $nis,
                    'text' => 'Paket ' . $package . $teksTransport
                ];
            }

            if ($checkTransaction > 0) {
                return [
                    'status' => 400,
                    'message' => 'Jatah nasi pada shift ini sudah diambil',
                    'nis' => $nis,
                    'text' => 'Paket ' . $package . $teksTransport
                ];
            }
        }

        if ($shift == 'MORNING') {
            if ($checkTransaction > 0) {
                return [
                    'status' => 400,
                    'message' => 'Jatah nasi pada shift ini sudah diambil',
                    'nis' => $nis,
                    'text' => 'Paket ' . $package . $teksTransport
                ];
            }
        }

        if ($shift == 'AFTERNOON') {
            $dayNum = date('N');

            $setTomorrow = new DateTime('tomorrow');
            $tomorrowDate = $setTomorrow->format('Y-m-d');
            $hour = '09:00:00';
            $format = 'Y-m-d H:i:s';
            $tomorrowDateTime = DateTime::createFromFormat($format, $tomorrowDate . ' ' . $hour);
            $tomorrowFinal = $tomorrowDateTime->format('Y-m-d H:i:s');

            if ($dayNum == 3 || $dayNum == 7) {
                //CHECK MORNING INI MONDAY
                $checkTomorrow = $this->db->select('id')->from('package_transaction')->where([
                    'package_id' => $packageID,
                    'DATE(created_at)' => $tomorrowDate,
                    'status' => 'MORNING'
                ])->get()->num_rows();

                if ($checkTransaction > 0) {
                    $now = $tomorrowFinal;
                    $shift = 'MORNING';
                }

                if ($checkTransaction > 0 && $checkTomorrow > 0) {
                    return [
                        'status' => 400,
                        'message' => 'Jatah nasi sahur sudah diambil',
                        'nis' => $nis,
                        'text' => 'Paket ' . $package . $teksTransport
                    ];
                }
            } else {
                if ($checkTransaction > 0) {
                    return [
                        'status' => 400,
                        'message' => 'Jatah nasi pada shift ini sudah diambil',
                        'nis' => $nis,
                        'text' => 'Paket ' . $package . $teksTransport
                    ];
                }
            }
        }

        $this->db->insert('package_transaction', [
            'package_id' => $packageID,
            'created_at' => $now,
            'amount' => 3000,
            'type' => $type,
            'status' => $shift
        ]);
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal menyimpan data. Kesalahan server..',
                'nis' => $nis,
                'text' => 'Paket ' . $package . $teksTransport
            ];
        }

        return [
            'status' => 200,
            'message' => 'Jatah nasi tersedia. Silahkan distribusikan..!!',
            'nis' => $nis,
            'text' => 'Paket ' . $package . $teksTransport
        ];
    }

    public function getShift()
    {
        $time = strtotime(date('H:i:s'));

        $data = $this->db->get('shifts')->result_object();
        foreach ($data as $d) {
            $begin = strtotime($d->begin);
            $finish = strtotime($d->finish);
            if ($time >= $begin and $time <= $finish) {
                return $d->name;
                break;
            }
        }
    }

    public function getData()
    {
        $data = $this->db->get_where('students', [
            'id' => $this->input->post('nis', true)
        ])->row_object();

        if (!$data) {
            return [
                'status' => 400
            ];
        }

        return [
            'status' => 200,
            'student' => $data,
            'package' => $this->input->post('text', true),
            'status_send' => $this->input->post('status', true),
            'message' => $this->input->post('message', true)
        ];
    }
}
