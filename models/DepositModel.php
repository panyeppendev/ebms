<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DepositModel extends CI_Model
{
    public function loadData()
    {
        $name = $this->input->post('name', true);
        $domicile = $this->input->post('domicile', true);

        $this->db->select('SUM(a.amount) AS deposit, b.id AS id, b.name, b.village, b.city, b.class, b.level, b.class_of_formal, b.level_of_formal, b.domicile');
        $this->db->from('deposit_credit AS a')->join('students AS b', 'b.id = a.student_id');
        if ($name != '') {
            $this->db->like('b.name', $name);
        }

        if ($domicile != '') {
            $this->db->where('b.domicile', $domicile);
        }

        $result = $this->db->group_by('a.student_id')->get();

        return [
            $result->result_object(),
            $result->num_rows()
        ];
    }

    public function detail()
    {
        $id = $this->input->post('id', true);

        $result = $this->db->get_where('deposit_credit', ['student_id' => $id]);
        return [
            $result->result_object(),
            $result->num_rows()
        ];
    }

    public function checkId()
    {
        $nis = str_replace('_', '', $this->input->post('id', true));

        $cekStudent = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->num_rows();
        if ($cekStudent <= 0) {
            return [
                'status' => 400,
                'message' => 'Santri tidak ditemukan'
            ];
        }

        $checkPurchase = $this->db->get_where('purchases', [
            'student_id' => $nis, 'status !=' => 'DONE'
        ])->row_object();
        if (!$checkPurchase) {
            return [
                'status' => 400,
                'message' => 'Santri ini memiliki paket'
            ];
        }

        return [
            'status' => 200,
            'nis' => $nis
        ];
    }

    public function showCheck()
    {
        $nis = $this->input->post('nis', true);

        $data = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();
        if (!$data) {
            return [
                'status' => 400
            ];
        }

		$credit = $this->getAmount($nis, 'deposit_credit');
		$debit = $this->getAmount($nis, 'deposit_debit');
		$balance = $credit - $debit;

        return [
            'status' => 200,
            'student' => $data,
			'credit' => $credit,
			'debit' => $debit,
			'balance' => $balance
        ];
    }

	public function getAmount($nis, $table)
	{
		$result = $this->db->select_sum('amount', 'credit')->where('student_id', $nis)->get($table)->row_object();
		if ($result) {
			return $result->credit;
		}

		return 0;
	}

    public function store()
    {
        $nominal = $this->input->post('nominal', true);
        $nis = $this->input->post('nis', true);

        if ($nominal == '' || $nominal <= 0) {
            return [
                'status' => 400,
                'message' => 'Nominal tidak boleh kosong'
            ];
        }

        $this->db->insert('deposit_credit', [
            'student_id' => $nis,
            'created_at' => date('Y-m-d'),
            'amount' => $nominal
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
