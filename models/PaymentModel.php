<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentModel extends CI_Model
{
	public function loadData()
	{
		$period = $this->dm->getperiod();
		$name = $this->input->post('name', true);
		$status = $this->input->post('status', true);
		$domicile = $this->input->post('domicile', true);

		$this->db->select('a.*, b.name, b.domicile, b.village, b.city')->from('payments as a');
		$this->db->join('students as b', 'b.id = a.student_id');
		$this->db->where('a.period', $period);
		if ($name != '') {
			$this->db->like('b.name', $name);
		}
		if ($status != '') {
			$this->db->where('a.status', $status);
		}
		if ($domicile != '') {
			$this->db->where('b.domicile', $domicile);
		}
		return $this->db->order_by('a.created_at', 'DESC')->get()->result_object();
	}
	public function setting()
	{
		$period = $this->dm->getperiod();
		$check = $this->db->get_where('payment_setting', ['period' => $period])->num_rows();
		if ($check > 0) {
			return 1;
		}
		return 0;
	}

	public function checkId()
	{
		$id = str_replace('_', '', $this->input->post('id', true));
		$checkStudent = $this->db->get_where('students', [
			'id' => $id,
			'status' => 'AKTIF'
		])->num_rows();
		if (!$checkStudent) {
			return [
				'status' => 400,
				'message' => 'ID Santri tidak valid'
			];
		}

		return [
			'status' => 200,
			'id' => $id,
			'message' => 'Sukses'
		];
	}

	public function showCheck()
	{
		$currentPeriod = $this->dm->getperiod();
		$id = $this->input->post('id', true);
		$data = $this->db->get_where('students', ['id' => $id])->row_object();
		$rate = $this->getRate();
		if (!$data) {
			return [
				0,
				0
			];
		}

		//CHECK PAYMENTS
		$checkPayment = $this->db->order_by('created_at', 'DESC')->get_where('payments', [
			'student_id' => $id, 'period' => $currentPeriod
		])->row_object();
		if ($checkPayment && $checkPayment->status === 'PAID') {
			return [
				$data,
				0
			];
		}

		if ($checkPayment && $checkPayment->status === 'NOT-PAID'){
			return [
				$data,
				$rate / 2,
				'Pembayaran tahap kedua tidak bisa dicicil'
			];
		}

		return [
			$data,
			$rate,
			'Biaya tahunan bisa dicicil sebanyak dua kali dengan masing-masing nominal: <b>Rp. '.number_format($rate/2, 0, ',', '.').'</b>'
		];
	}

	public function getRate()
	{
		$currentPeriod = $this->dm->getperiod();

		$old = $this->db->get_where('rates', ['type' => 'OLD', 'period' => $currentPeriod])->row_object();
		if (!$old) {
			return 0;
		}

		return (int)$old->amount;
	}

	public function idGenerator()
	{
		$now = getMasehiExplode();
		return $now[2] . $now[1] . $now[0] . mt_rand(0000, 9999);
	}

	public function save()
	{
		$id = $this->input->post('id', true);
		$nominal = (int)str_replace('.', '', $this->input->post('nominal', true));
		$checkStudent = $this->db->get_where('students', [
			'id' => $id, 'status' => 'AKTIF'
		])->row_object();
		if (!$checkStudent) {
			return [
				'status' => 400,
				'message' => 'ID tidak valid'
			];
		}

		if (!$nominal || $nominal < 0) {
			return [
				'status' => 400,
				'message' => 'Pastikan nominal diisi'
			];
		}
		$currentPeriod = $this->dm->getperiod();

		$getDetail = $this->db->get_where('payment_rate_detail', [
			'type' => 'OLD', 'period' => $currentPeriod
		])->result_object();
		if (!$getDetail) {
			return [
				'status' => 400,
				'message' => 'Detail biaya tahunan belum diatur',
			];
		}

		//CHECK PAYMENTS
		$checkPayment = $this->db->order_by('created_at', 'DESC')->get_where('payments', [
			'student_id' => $id, 'period' => $currentPeriod
		])->row_object();
		if ($checkPayment && $checkPayment->status === 'PAID') {
			return [
				'status' => 400,
				'message' => 'Santri ini sebelumnya sudah melunasi biaya tahunan',
			];
		}

		$old = $this->getRate();
		$half = (int)$old / 2;

		if ($checkPayment) {
			if ($nominal != $half) {
				if ($nominal < $half) {
					return [
						'status' => 400,
						'message' => 'Pembayaran tahap kedua harus dibayar penuh',
					];
				}else {
					return [
						'status' => 400,
						'message' => 'Nominal yang dimasukkan tidak valid',
					];
				}
			}
		}else if (!in_array($nominal, [$old, $half], false)) {
			return [
				'status' => 400,
				'message' => 'Nominal yang dimasukkan tidak valid',
			];
		}


		if ($checkPayment) {
			$bill = $old / 2;
			$status = 'PAID';
			$bagi = 2;
		}else{
			$bill = $old;
			if ($old === $nominal) {
				$status = 'PAID';
				$bagi = 1;
			}else{
				$status = 'NOT-PAID';
				$bagi = 2;
			}
		}

		$invoice = $this->idGenerator();
		$this->db->insert('payments', [
			'id' => $invoice,
			'student_id' => $id,
			'bill' => $bill,
			'amount' => $nominal,
			'status' => $status,
			'created_at' => date('Y-m-d H:i:s'),
			'period' => $currentPeriod
		]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Server gagal menyimpan data pembayaran',
			];
		}

		$rows = 0;
		foreach ($getDetail as $item) {
			$this->db->insert('payment_detail', [
				'payment_id' => $invoice,
				'account_id' => $item->account_id,
				'amount' => (int)$item->amount / $bagi
			]);
			$rows++;
		}

		if ($rows <= 0) {
			return [
				'status' => 400,
				'message' => 'Server gagal menyimpan data detail pembayaran',
			];
		}


		return [
			'status' => 200,
			'message' => 'Sukses',
			'invoice' => $invoice
		];
	}

	public function dataPrint($package)
	{
		$getPackage = $this->db->get_where('payments', [
			'id' => $package
		])->row_object();
		$id = $getPackage->student_id;
		$student = $this->db->get_where('students', ['id' => $id])->row_object();

		$this->db->select('a.*, b.name')->from('payment_detail AS a');
		$this->db->join('payment_account AS b', 'a.account_id = b.id');
		$this->db->where('a.payment_id', $package);
		$detail = $this->db->order_by('a.account_id', 'ASC')->get()->result_object();

		return [$getPackage, $student, $detail];
	}

}
