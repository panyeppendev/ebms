<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DepositModel extends CI_Model
{
    public function step()
    {
        $data = $this->db->get_where('steps', ['status' => 'PAYMENT'])->row_object();
        if ($data) {
            return $data->step;
        } else {
            return 0;
        }
    }

    public function loadData()
    {
        $name = $this->input->post('name', true);
        $domicile = $this->input->post('domicile', true);

        $this->db->select('a.*, b.name, b.village, b.city, b.class, b.level, b.class_of_formal, b.level_of_formal, b.domicile');
        $this->db->from('packages AS a')->join('students AS b', 'b.id = a.student_id');
        if ($name != '') {
            $this->db->like('b.name', $name);
        }

        if ($domicile != '') {
            $this->db->where('b.domicile', $domicile);
        }
        $this->db->where('a.deposit >', 0);
        $result = $this->db->get();

        return [
            $result->result_object(),
            $result->num_rows()
        ];
    }

    public function detail()
    {
        $id = $this->input->post('id', true);

        $result = $this->db->select('*')->from('package_deposit')->where('package_id', $id)->get();
        return [
            $result->result_object(),
            $result->num_rows()
        ];
    }

    public function checkId()
    {
        $nis = str_replace('_', '', $this->input->post('id', true));
        $step = $this->input->post('step', true);
        $period = $this->dm->getperiod();

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
            'step' => $step, 'status !=' => 'INORDER'
        ])->row_object();
        if (!$checkPackage) {
            return [
                'status' => 400,
                'message' => 'Santri ini belum beli paket tahap ke-' . $step . ' ini'
            ];
        }

        $packageID = $checkPackage->id;
        $package = $checkPackage->package;
        $transport = $checkPackage->transport;
        $deposit = $checkPackage->deposit;

        //TRANSPORT
        $teksTransport = ['', ' + Transport'];

        return [
            'status' => 200,
            'message' => $nis,
            'package' => $packageID,
            'total' => $deposit,
            'text' => 'Paket ' . $package . $teksTransport[$transport]
        ];
    }

    public function showCheck()
    {
        $nis = $this->input->post('nis', true);
        $total = $this->input->post('total', true);
        $text = $this->input->post('text', true);

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
            'package' => $text,
            'total' => $total
        ];
    }

    public function save()
    {
        $nominalRp = str_replace('.', '', $this->input->post('nominal', true));
        $nominal = (int)$nominalRp;
        $package = $this->input->post('package', true);
        $total = $this->input->post('total', true);

        if ($package == 0 || $package == '') {
            return [
                'status' => 400,
                'message' => 'ID Paket tidak valid. Refresh halaman!'
            ];
        }

        if ($nominal == '' || $nominal <= 0) {
            return [
                'status' => 400,
                'message' => 'Nominal tidak boleh kosong'
            ];
        }

        $this->db->insert('package_deposit', [
            'package_id' => $package,
            'created_at' => date('Y-m-d H:i:s'),
            'amount' => $nominal
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Server tidak merespon. Coba refresh halaman!'
            ];
        }

        $this->db->where('id', $package)->update('packages', [
            'deposit' => $total + $nominal,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Server tidak merespon. Coba refresh halaman!'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Tabungan berhasil disimpan'
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
