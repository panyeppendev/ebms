<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BuyModel extends CI_Model
{
    public function step()
    {
        $data = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->row_object();
        if ($data) {
            return [$data->step, $data->start_at];
        } else {
            return [0, date('Y-m-d')];
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

    public function checkNis()
    {
        $nis = str_replace('_', '', $this->input->post('nis', true));
        $step = $this->input->post('step', true);
        $start = $this->step()[1];
        $period = $this->dm->getperiod();
        $masehi = date('Y-m-d');

        if (date('Y-m-d', strtotime($start)) > $masehi) {
            return [
                'status' => 500,
                'message' => 'Pencairan tahap ' . $step . ' belum dibuka'
            ];
        }

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
                'status' => 400,
                'message' => 'Santri ini tidak punya paket aktif pada tahap saat ini',
                'nis' => $nis,
                'package' => 0
            ];
        }

        $checkNominal = $this->checkNominal();
        if ($checkNominal['status'] == 400) {
            return [
                'status' => 500,
                'message' => 'Tarif pangkas rambut belum diatur. Hubungi Admin ~',
                'nis' => $nis,
                'package' => 0
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'package' => $checkPackage->id,
            'nominal' => $checkNominal['nominal'],
            'rp' => $checkNominal['rp']
        ];
    }

    public function getData()
    {
        $nis = $this->input->post('nis', true);
        $package = $this->input->post('package', true);

        $data = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();

        return [
            'student' => $data,
            'package' => $this->getDetailPackage($package),
            'pocket' => $this->getPocket($package, $this->getDetailPackage($package)['limit']),
            'daily' => $this->getPocketDaily($package, $this->getDetailPackage($package)['pocket']),
            'deposit' => $this->getDeposit($nis)
        ];
    }

    public function getDetailPackage($packageID)
    {
        $getPackage = $this->db->get_where('packages', ['id' => $packageID])->row_object();

        if (!$getPackage) {
            return [
                'status' => 400,
                'message' => 'Data paket tidak valid',
                'pocket' => 0,
                'limit' => 0
            ];
        }

        $package = $getPackage->package;
        $transport = $getPackage->transport;
        $teksTransport = ['', ' + Transport'];
        if ($package == 'A' || $package == 'B') {
            $pocket = 5000;
            $limit = 150000;
        } elseif ($package == 'C' || $package == 'D') {
            $pocket = 10000;
            $limit = 300000;
        } else {
            $pocket = 0;
            $limit = 0;
        }

        return [
            'status' => 200,
            'info' => 'Paket ' . $package . $teksTransport[$transport],
            'pocket' => $pocket,
            'limit' => $limit
        ];
    }

    public function getPocket($package, $limit)
    {
        $cash = $this->db->select('SUM(amount) AS total')->from('package_transaction')->where([
            'package_id' => $package, 'status' => 'POCKET_CASH'
        ])->get()->row_object();
        if ($cash && $cash->total != '' || $cash->total != 0) {
            $cash = $cash->total;
        } else {
            $cash = 0;
        }

        $this->db->select('SUM(amount) AS total')->from('package_transaction')->where('package_id', $package);
        $nonCash = $this->db->where_in('status', [
            'POCKET_CANTEEN',
            'POCKET_STORE',
            'POCKET_LIBRARY',
            'POCKET_SECURITY',
            'POCKET_BARBER'
        ])->get()->row_object();
        if ($nonCash && $nonCash->total != '' || $nonCash->total != 0) {
            $nonCash = $nonCash->total;
        } else {
            $nonCash = 0;
        }

        return [
            'limit' => $limit,
            'cash' => $cash,
            'noncash' => $nonCash,
            'total' => $limit - ($cash + $nonCash)
        ];
    }

    public function getPocketDaily($package, $pocket)
    {
        $start = $this->step()[1];
        $masehi = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d"))));
        $beforeYesterday = date('Y-m-d', strtotime("-2 day", strtotime(date("Y-m-d"))));

        //CEK SALDO
        $firstDiff = new DateTime($start);
        $secondDiff = new DateTime($masehi);
        $diffDate = $secondDiff->diff($firstDiff);

        //CEK KEMARIN
        $checkYesterday = $this->db->get_where('package_transaction', [
            'DATE(created_at)' => $yesterday, 'type' => 'POCKET',
            'package_id' => $package
        ])->num_rows();

        $residual = 0;
        if ($diffDate->d >= 2) {
            if ($checkYesterday <= 0) {
                $checkBeforeYesterday = $this->db->get_where('package_transaction', [
                    'DATE(created_at)' => $beforeYesterday, 'type' => 'POCKET',
                    'package_id' => $package
                ])->num_rows();

                if ($checkBeforeYesterday <= 0) {
                    $residual = $pocket * 2;
                } else {
                    $residual = $pocket;
                }
            } else {
                $residual;
            }
        }

        $cash = $this->db->select('SUM(amount) AS total')->from('package_transaction')->where([
            'package_id' => $package, 'status' => 'POCKET_CASH', 'DATE(created_at)' => $masehi
        ])->get()->row_object();
        if ($cash && $cash->total != '' || $cash->total != 0) {
            $cash = $cash->total;
        } else {
            $cash = 0;
        }

        $this->db->select('SUM(amount) AS total')->from('package_transaction');
        $this->db->where(['package_id' => $package, 'DATE(created_at)' => $masehi]);
        $nonCash = $this->db->where_in('status', [
            'POCKET_CANTEEN',
            'POCKET_STORE',
            'POCKET_LIBRARY',
            'POCKET_SECURITY',
            'POCKET_BARBER'
        ])->get()->row_object();
        if ($nonCash && $nonCash->total != '' || $nonCash->total != 0) {
            $nonCash = $nonCash->total;
        } else {
            $nonCash = 0;
        }

        $total = ($pocket + $residual) - ($cash + $nonCash);

        return [
            'limit' => $pocket,
            'residual' => $residual,
            'cash' => $cash,
            'noncash' => $nonCash,
            'total' => $total
        ];
    }

    public function getDeposit($nis)
    {
        $period = $this->dm->getperiod();

        $kredit = $this->db->select('SUM(deposit) AS deposit')->from('packages')->where([
            'student_id' => $nis, 'period' => $period
        ])->get()->row_object();
        if (!$kredit || $kredit->deposit == '') {
            $kredit = 0;
        } else {
            $kredit = $kredit->deposit;
        }

        $this->db->select('a.student_id, SUM(b.amount) AS amount')->from('packages AS a');
        $this->db->join('package_transaction AS b', 'b.package_id = a.id');
        $cash = $this->db->where([
            'a.student_id' => $nis, 'a.period' => $period, 'b.status' => 'DEPOSIT_CASH'
        ])->get()->row_object();
        if (!$cash || $cash->amount == '') {
            $cash = 0;
        } else {
            $cash = $cash->amount;
        }

        $this->db->select('a.student_id, SUM(b.amount) AS amount')->from('packages AS a');
        $this->db->join('package_transaction AS b', 'b.package_id = a.id');
        $this->db->where_in('b.status', [
            'DEPOSIT_CANTEEN',
            'DEPOSIT_STORE',
            'DEPOSIT_LIBRARY',
            'DEPOSIT_SECURITY',
            'DEPOSIT_BARBER'
        ]);
        $nonCash = $this->db->where([
            'a.student_id' => $nis, 'a.period' => $period
        ])->get()->row_object();
        if (!$nonCash || $nonCash->amount == '') {
            $nonCash = 0;
        } else {
            $nonCash = $nonCash->amount;
        }

        $total = $kredit - ($cash + $nonCash);
        return [
            'kredit' => $kredit,
            'cash' => $cash,
            'noncash' => $nonCash,
            'total' => $total
        ];
    }

    public function save()
    {
        $package = $this->input->post('package', true);
        $nis = $this->input->post('nis', true);
        $nominal = $this->input->post('nominal', true);
        $step = $this->input->post('step', true);
        $status = $this->input->post('status', true);

        if ($status == 'POCKET') {
            $detailPackage = $this->getDetailPackage($package);
            if ($detailPackage['status'] == 400) {
                return [
                    'status' => 400,
                    'message' => $detailPackage['message']
                ];
            }

            $pocket = $detailPackage['pocket'];
            $limit = $detailPackage['limit'];

            $getPocket = $this->getPocket($package, $limit);
            $totalPocket = $getPocket['total'];

            $pocketDaily = $this->getPocketDaily($package, $pocket);
            $totalPocketDaily = $pocketDaily['total'];
            if ($totalPocket <= 0) {
                $totalPocketDaily = 0;
            } else {
                $totalPocketDaily;
            }

            $deposit = $this->getDeposit($nis);
            $totalDeposit = $deposit['total'];

            $total = $totalPocketDaily + $totalDeposit;

            if ($nominal > $total) {
                return [
                    'status' => 400,
                    'message' => 'Saldo pencairan tidak cukup'
                ];
            }

            //JIKA LIMIT SUDAH HABIS
            if ($totalPocket <= 0) {
                $this->db->insert('package_transaction', [
                    'student_id' => $nis,
                    'package_id' => $package,
                    'created_at' => date('Y-m-d H:i:s'),
                    'amount' => $nominal,
                    'type' => 'DEPOSIT',
                    'status' => 'DEPOSIT_BARBER'
                ]);
            } else {
                //JIKA NOMINAL > UANG SAKU HARIAN MAKA DEPOSITS KURANGI
                if ($nominal > $totalPocketDaily) {
                    $depositNominal = $nominal - $totalPocketDaily;
                    $nominal = $totalPocketDaily;
                    $this->db->insert('package_transaction', [
                        'student_id' => $nis,
                        'package_id' => $package,
                        'created_at' => date('Y-m-d H:i:s'),
                        'amount' => $depositNominal,
                        'type' => 'DEPOSIT',
                        'status' => 'DEPOSIT_BARBER'
                    ]);
                }

                if ($nominal > 0) {
                    $this->db->insert('package_transaction', [
                        'student_id' => $nis,
                        'package_id' => $package,
                        'created_at' => date('Y-m-d H:i:s'),
                        'amount' => $nominal,
                        'type' => 'POCKET',
                        'status' => 'POCKET_BARBER'
                    ]);
                }
            }
        }

        $this->db->insert('payment_security', [
            'student_id' => $nis,
            'step' => $step,
            'amount' => $nominal,
            'created_at' => date('Y-m-d H:i:s'),
            'period' => $this->dm->getperiod(),
            'type' => 'BARBER',
            'status' => $status
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal menyimpan data'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'package' => $package
        ];
    }

    public function checkNominal()
    {
        $data = $this->db->get_where('store_setting', [
            'name' => 'BARBER'
        ])->row_object();
        if (!$data) {
            return [
                'status' => 400,
                'nominal' => 0,
                'rp' => 'Rp. '.number_format(0, 0, ',', '.')
            ];
        }

        return [
            'status' => 200,
            'nominal' => $data->price,
            'rp' => 'Rp. '.number_format($data->price, 0, ',', '.')
        ];
    }

    public function loadRecap()
    {
        $name = $this->input->post('name', true);
        $filter = $this->input->post('filter', true);
        $now = date('Y-m-d');
        $this->db->select('SUM(amount) AS amount')->from('payment_security');
        if ($filter != '') {
            $this->db->where('DATE(created_at)', $filter);
        } else {
            $this->db->where('DATE(created_at)', $now);
        }
        $amount = $this->db->get()->row_object();
        if ($amount) {
            if ($amount->amount != '' || $amount->amount != 0) {
                $amount = $amount->amount;
            } else {
                $amount = 0;
            }
        } else {
            $amount = 0;
        }

        $this->db->select('a.amount, a.created_at, a.status, a.type, b.name, b.domicile');
        $this->db->from('payment_security AS a');
        $this->db->join('students AS b', 'b.id = a.student_id');
        if ($filter != '') {
            $this->db->where('DATE(a.created_at)', $filter);
        } else {
            $this->db->where('DATE(a.created_at)', $now);
        }
        if ($name != '') {
            $this->db->like('b.name', $name);
        }
        $result = $this->db->get();

        return [
            $amount,
            $result->num_rows(),
            $result->result_object()
        ];
    }
}
