<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DisbursementModel extends CI_Model
{
	public function disbursements()
	{
		$date = $this->input->post('date', true);
		$name = $this->input->post('name', true);
		$this->db->select('a.amount, a.id, b.name, b.village, b.city, b.domicile, b.class, b.level, b.class_of_formal, b.level_of_formal')->from('disbursements as a');
		$this->db->join('students as b', 'b.id = a.student_id')->where('a.created_at', $date);
		if ($name) {
			$this->db->like('b.name', $name);
		}
		return $this->db->order_by('a.id', 'ASC')->get()->result_object();
	}
	public function setting()
	{
		return $this->db->get_where('set_daily', ['status' => 'OPEN'])->row_object();
	}

	public function account()
	{
		return $this->db->get('account_pocket')->row_object();
	}

	public function dailyTotal()
	{
		$date = $this->input->post('date', true);
		$data = $this->db->select('SUM(amount) as total')->from('disbursements')->where([
			'created_at' => $date
		])->get()->row_object();

		if ($data) {
			return 'Rp. '.number_format($data->total, 0, ',', '.');
		}

		return 'Rp. 0';
	}

    public function checkCard()
    {
        $card = str_replace('_', '', $this->input->post('card', true));
        
		$setting = $this->setting();
		if (!$setting) {
			return [
				'status' => 500,
				'message' => 'Transaksi hari ini belum dibuka'
			];
		}

		$account = $this->account();
		if (!$account) {
			return [
				'status' => 500,
				'message' => 'Rekening pencairan belum diatur'
			];
		}

        //CHECK CARD
        $checkCard = $this->db->get_where('cards', ['id' => $card])->row_object();
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
        ])->row_object();
        if (!$cekStudent) {
            return [
                'status' => 500,
                'message' => 'Santri tidak ditemukan'
            ];
        }

		$checkPurchase = $this->db->get_where('purchases', [
			'student_id' => $nis, 'status' => 'ACTIVE'
		])->row_object();
		if (!$checkPurchase) {
			return [
				'status' => 500,
				'message' => 'Belum ada paket aktif'
			];
		}

		$balance = $this->getBalance($nis);
		$disbursement = $this->getDisbursement($nis, $setting->created_at);
        return [
            'status' => 200,
            'balance' => $balance[0],
			'disbursement' => $disbursement,
			'total' => $balance[0] - $disbursement,
            'nis' => $nis,
            'purchase' => $checkPurchase->id,
			'account' => $account->account_id,
			'date' => $setting->created_at
        ];
    }

	public function getData()
	{
		$nis = $this->input->post('nis', true);
		$setting = $this->setting();

		$name = '';
		$address = '';
		$domicile = '';
		$diniyah = '';
		$formal = '';
		$package = '';
		$daily = '';
		$reserved = '';
		$disbursement = '';

		$student = $this->db->get_where('students', ['id' => $nis])->row_object();

		$this->db->select('b.name')->from('purchases as a')->join('packages as b', 'b.id = a.package_id');
		$purchase = $this->db->where(['a.student_id' => $nis, 'a.status' => 'ACTIVE'])->get()->row_object();
		if ($student) {
			$name = $student->name;
			$address = $student->village.', '.str_replace(['Kota ', 'Kabupaten '], '', $student->city);
			$domicile = $student->domicile;
			$diniyah = $student->class.' - '.$student->level;
			$formal = $student->class_of_formal.' - '.$student->level_of_formal;
		}

		if ($purchase) {
			$package = $purchase->name;
		}

		$balance = $this->getBalance($nis);
		if ($balance) {
			$daily = $balance[0];
			$reserved = $balance[1];
		}

		$disbursementLog = $this->getDisbursement($nis, $setting->created_at);
		if ($disbursementLog) {
			$disbursement = $disbursementLog;
		}

		return [
			'nis' => $nis,
			'name' => $name,
			'address' => $address,
			'domicile' => $domicile,
			'diniyah' => $diniyah,
			'formal' => $formal,
			'package' => $package,
			'daily' => $daily,
			'reserved' => $reserved,
			'disbursement' => $disbursement,
			'total' => $daily - $disbursement
		];
	}

	public function getBalance($id)
	{
		$data = $this->db->get_where('daily_pocket_limit', [
			'student_id' => $id
		])->row_object();
		if ($data) {
			return [$data->pocket, $data->reserved];
		}

		return [0, 0];
	}

	public function getDisbursement($nis, $date)
	{
		$data = $this->db->select('SUM(amount) as total')->from('disbursements')->where([
			'student_id' => $nis, 'created_at' => $date
		])->get()->row_object();

		if ($data) {
			return $data->total;
		}

		return 0;
	}

    public function save()
    {
        $date = $this->input->post('date', true);
        $nis = $this->input->post('nis', true);
        $purchase = $this->input->post('purchase', true);
        $account = $this->input->post('account', true);
        $total = $this->input->post('total', true);
        $nominal = $this->input->post('nominal_real', true);

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

        $this->db->insert('disbursements', [
			'student_id' => $nis,
			'purchase_id' => $purchase,
			'account_id' => $account,
			'amount' => $nominal,
			'role_id' => $this->session->userdata('role_id'),
			'created_at' => $date
		]);

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

	public function destroy()
	{
		$id = $this->input->post('id', true);
		$this->db->where('id', $id)->delete('disbursements');
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal menghapus data'
			];
		}

		return [
			'status' => 200,
			'message' => 'Satu transaksi berhasil dihapus'
		];
    }
}
