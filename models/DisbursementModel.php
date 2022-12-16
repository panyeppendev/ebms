<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DisbursementModel extends CI_Model
{
    public function step()
    {
        $data = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->row_object();
        if ($data) {
            return [$data->step, $data->start_at];
        } else {
            return [0];
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
        $start = $this->input->post('start', true);
        $period = $this->dm->getperiod();
        $masehi = date('Y-m-d');

        //CHECK CARD
        $checkCard = $this->db->get_where('cards', ['id' => $nis])->row_object();
        if (!$checkCard) {
            return [
                'status' => 400,
                'message' => 'Kartu tidak valid'
            ];
        }

        $statusCard = $checkCard->status;
        if ($statusCard != 'ACTIVE') {
            $statusText = ['INACTIVE' => 'belum diaktivasi', 'BLOCKED' => 'sudah diblokir'];
            return [
                'status' => 400,
                'message' => 'Kartu ini ' . $statusText[$statusCard]
            ];
        }

        $nis = $checkCard->student_id;

        $cekStudent = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->num_rows();
        if ($cekStudent <= 0) {
            return [
                'status' => 400,
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
                'message' => 'Santri ini tidak punya paket aktif pada tahap saat ini'
            ];
        }

        if (date('Y-m-d', strtotime($start)) > $masehi) {
            return [
                'status' => 400,
                'message' => 'Pencairan tahap ' . $step . ' belum dibuka'
            ];
        }


        $packageID = $checkPackage->id;
        $package = $checkPackage->package;
        $transport = $checkPackage->transport;
        if ($package == 'A' || $package == 'B') {
            $pocket = 5000;
        } elseif ($package == 'C' || $package == 'D') {
            $pocket = 10000;
        } else {
            $pocket = 0;
        }

        //GET DEPOSIT
        $depositKredit = $this->db->select('SUM(deposit) AS deposit')->from('packages')->where([
            'student_id' => $nis, 'period' => $period
        ])->get()->row_object();
        if (!$depositKredit || $depositKredit->deposit == '') {
            $depositKredit = 0;
        } else {
            $depositKredit = $depositKredit->deposit;
        }

        $this->db->select('a.student_id, SUM(b.amount) AS amount')->from('packages AS a');
        $this->db->join('package_transaction AS b', 'b.package_id = a.id');
        $depositDebet = $this->db->where([
            'a.student_id' => $nis, 'a.period' => $period, 'b.type' => 'DEPOSIT'
        ])->get()->row_object();
        if (!$depositDebet || $depositDebet->amount == '') {
            $depositDebet = 0;
        } else {
            $depositDebet = $depositDebet->amount;
        }
        $deposit = $depositKredit - $depositDebet;

        $yesterday = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d"))));
        $beforeYesterday = date('Y-m-d', strtotime("-2 day", strtotime(date("Y-m-d"))));

        //CEK SALDO
        $firstDiff = new DateTime($start);
        $secondDiff = new DateTime($masehi);
        $diffDate = $secondDiff->diff($firstDiff);

        //CEK KEMARIN
        $checkYesterday = $this->db->get_where('package_transaction', [
            'DATE(created_at)' => $yesterday, 'type' => 'POCKET',
            'package_id' => $packageID
        ])->num_rows();

        if ($diffDate->d >= 2) {
            if ($checkYesterday > 1) {
                $pocketFinal = $pocket;
            } else {
                $this->db->select('SUM(amount) AS amount')->from('package_transaction');
                $this->db->where([
                    'DATE(created_at) >=' => $beforeYesterday,
                    'DATE(created_at) <=' => $yesterday,
                    'type' => 'POCKET',
                    'package_id' => $packageID
                ]);
                $data = $this->db->get()->row_object();
                if ($data->amount == '' || $data->amount <= 0) {
                    $pocketFinal = $pocket * 3;
                } else {
                    $pocketFinal = $pocket * 2;
                }
            }
        } else {
            $pocketFinal = $pocket;
        }

        //CEK KREDIT HARI INI
        $this->db->select('SUM(amount) AS amount')->from('package_transaction');
        $this->db->where([
            'DATE(created_at)' => $masehi, 'type' => 'POCKET',
            'package_id' => $packageID
        ]);
        $checkKreditNow = $this->db->get()->row_object();
        if ($checkKreditNow->amount == '' || $checkKreditNow->amount <= 0) {
            $kredit = 0;
        } else {
            $kredit = $checkKreditNow->amount;
        }

        $total = ($pocketFinal + $deposit) - $kredit;


        //TRANSPORT
        $teksTransport = ['', ' + Transport'];

        if ($total <= 0) {
            return [
                'status' => 400,
                'message' => 'Uang saku hari ini sudah habis'
            ];
        }

        if ($total > 0 && $total < 5000) {
            return [
                'status' => 400,
                'message' => 'Uang saku di bawah 5rb tidak bisa dilayani secara tunai'
            ];
        }

        //CEK KREDIT TUNAI
        $this->db->select('SUM(amount) AS amount')->from('package_transaction');
        $this->db->where([
            'DATE(created_at)' => $masehi,
            'package_id' => $packageID
        ]);
        $this->db->where_in('status', ['POCKET_CASH', 'DEPOSIT_CASH']);
        $checkKreditCash = $this->db->get()->row_object();
        if ($checkKreditCash->amount == '' || $checkKreditCash->amount <= 0) {
            $kreditCash = 0;
        } else {
            $kreditCash = $checkKreditCash->amount;
        }

        //CEK KREDIT NON-TUNAI
        $this->db->select('SUM(amount) AS amount')->from('package_transaction');
        $this->db->where([
            'DATE(created_at)' => $masehi,
            'package_id' => $packageID
        ]);
        $this->db->where_in('status', [
            'POCKET_CANTEEN',
            'DEPOSIT_CANTEEN',
            'POCKET_STORE',
            'DEPOSIT_STORE'
        ]);
        $checkKreditCanteen = $this->db->get()->row_object();
        if ($checkKreditCanteen->amount == '' || $checkKreditCanteen->amount <= 0) {
            $kreditCanteen = 0;
        } else {
            $kreditCanteen = $checkKreditCanteen->amount;
        }

        return [
            'status' => 200,
            'message' => $nis,
            'package' => $packageID,
            'pocket' => $pocketFinal - $kredit,
            'deposit' => $deposit,
            'cash' => $kreditCash,
            'canteen' => $kreditCanteen,
            'total' => $total,
            'text' => 'Paket ' . $package . $teksTransport[$transport]
        ];
    }

    public function getData()
    {
        $nis = $this->input->post('nis', true);
        $pocket = $this->input->post('pocket', true);
        $deposit = $this->input->post('deposit', true);
        $cash = $this->input->post('cash', true);
        $canteen = $this->input->post('canteen', true);
        $total = $this->input->post('total', true);
        $package = $this->input->post('package', true);

        $data = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();
        if (!$data) {
            return [
                'status' => 400
            ];
        }

        return [
            'status' => 200,
            'student' => $data,
            'pocket' => $pocket,
            'deposit' => $deposit,
            'cash' => $cash,
            'canteen' => $canteen,
            'total' => $total,
            'package' => $package
        ];
    }

    public function save()
    {
        $nominalRp = str_replace('.', '', $this->input->post('nominal', true));
        $nominal = (int)$nominalRp;
        $package = $this->input->post('package_save', true);
        $pocket = $this->input->post('pocket_save', true);
        $total = $this->input->post('total_save', true);

        if ($nominal == '' || $nominal <= 0) {
            return [
                'status' => 400,
                'message' => 'Nominal tidak boleh kosong'
            ];
        }

        if ($nominal > $total) {
            return [
                'status' => 400,
                'message' => 'Nominal tidak boleh lebih besar dari dana pencairan'
            ];
        }

        if ($nominal < 5000) {
            return [
                'status' => 400,
                'message' => 'Nominal minimal Rp. 5.000'
            ];
        }

        if ($nominal % 5000 != 0) {
            return [
                'status' => 400,
                'message' => 'Nominal harus kelipatan Rp. 5.000'
            ];
        }

        //JIKA NOMINAL > UANG SAKU HARIAN MAKA DEPOSITS KURANGI
        if ($nominal > $pocket) {
            $depositNominal = $nominal - $pocket;
            $nominal = $pocket;
            $this->db->insert('package_transaction', [
                'package_id' => $package,
                'created_at' => date('Y-m-d H:i:s'),
                'amount' => $depositNominal,
                'type' => 'DEPOSIT',
                'status' => 'DEPOSIT_CASH'
            ]);
        }

        if ($nominal > 0) {
            $this->db->insert('package_transaction', [
                'package_id' => $package,
                'created_at' => date('Y-m-d H:i:s'),
                'amount' => $nominal,
                'type' => 'POCKET',
                'status' => 'POCKET_CASH'
            ]);
        }

        return [
            'status' => 200,
            'message' => 'Pencairan cash sukses'
        ];
    }

    public function loadRecap()
    {
        $now = date('Y-m-d');
        $this->db->select('SUM(amount) AS amount')->from('package_transaction');
        $this->db->where('DATE(created_at)', $now);
        $this->db->where_in('status', ['POCKET_CASH', 'DEPOSIT_CASH']);
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

        $this->db->select('a.amount, a.created_at, a.type, b.id AS package, c.name');
        $this->db->from('package_transaction AS a')->join('packages AS b', 'b.id = a.package_id');
        $this->db->join('students AS c', 'c.id = b.student_id');
        $this->db->where('DATE(a.created_at)', $now);
        $this->db->where_in('a.status', ['POCKET_CASH', 'DEPOSIT_CASH']);
        $result = $this->db->get();

        return [
            $amount,
            $result->num_rows(),
            $result->result_object()
        ];
    }
}
