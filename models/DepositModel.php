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
        $period = $this->dm->getperiod();

        $this->db->select('SUM(a.deposit) AS deposit, b.id AS id, b.name, b.village, b.city, b.class, b.level, b.class_of_formal, b.level_of_formal, b.domicile');
        $this->db->from('packages AS a')->join('students AS b', 'b.id = a.student_id');
        if ($name != '') {
            $this->db->like('b.name', $name);
        }

        if ($domicile != '') {
            $this->db->where('b.domicile', $domicile);
        }
        $this->db->where([
            'deposit >' => 0, 'a.period' => $period
        ]);
        $result = $this->db->group_by('a.student_id')->get();

        return [
            $result->result_object(),
            $result->num_rows()
        ];
    }

    public function detail()
    {
        $id = $this->input->post('id', true);
        $period = $this->dm->getperiod();

        $this->db->select('b.*')->from('package_deposit AS b');
        $this->db->join('packages AS a', 'a.id = b.package_id');
        $result = $this->db->where([
            'a.student_id' => $id,
            'a.period' => $period
        ])->get();
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
        $deposit = $checkPackage->deposit;

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
        $depositResidual = $depositKredit - $depositDebet;

        return [
            'status' => 200,
            'message' => $nis,
            'package' => $packageID,
            'kredit' => $depositKredit,
            'debet' => $depositDebet,
            'total' => $depositResidual,
            'saldo' => $deposit
        ];
    }

    public function showCheck()
    {
        $nis = $this->input->post('nis', true);
        $kredit = $this->input->post('kredit', true);
        $debet = $this->input->post('debet', true);
        $total = $this->input->post('total', true);

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
            'kredit' => $kredit,
            'debet' => $debet,
            'total' => $total
        ];
    }

    public function save()
    {
        $nominalRp = str_replace('.', '', $this->input->post('nominal', true));
        $nominal = (int)$nominalRp;
        $package = $this->input->post('package', true);
        $saldo = $this->input->post('saldo', true);

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
            'deposit' => $saldo + $nominal,
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
}
