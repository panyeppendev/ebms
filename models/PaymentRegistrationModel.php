<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentRegistrationModel extends CI_Model
{
	public function loadData()
	{
		$period = $this->dm->getperiod();
		$name = $this->input->post('name', true);
		$domicile = $this->input->post('domicile', true);

		$this->db->select('a.*, b.name, b.domicile, b.village, b.city')->from('payment_registration as a');
		$this->db->join('students as b', 'b.id = a.student_id');
		$this->db->where('a.period', $period);
		if ($name != '') {
			$this->db->like('b.name', $name);
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
		$currentPeriod = $this->dm->getperiod();

		$checkStudent = $this->db->get_where('students', [
			'id' => $id,
			'status' => 'AKTIF',
			'period' => $currentPeriod
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
		$checkPayment = $this->db->order_by('created_at', 'DESC')->get_where('payment_registration', [
			'student_id' => $id
		])->row_object();
		if ($checkPayment) {
			return [
				$data,
				0
			];
		}

		return [
			$data,
			$rate
		];
	}

	public function getRate()
	{
		$currentPeriod = $this->dm->getperiod();

		$old = $this->db->get_where('rates', ['type' => 'NEW', 'period' => $currentPeriod])->row_object();
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
			'type' => 'NEW', 'period' => $currentPeriod
		])->result_object();
		if (!$getDetail) {
			return [
				'status' => 400,
				'message' => 'Detail biaya pendaftaran belum diatur',
			];
		}

		//CHECK PAYMENTS
		$checkPayment = $this->db->order_by('created_at', 'DESC')->get_where('payment_registration', [
			'student_id' => $id
		])->row_object();
		if ($checkPayment) {
			return [
				'status' => 400,
				'message' => 'Santri ini sebelumnya sudah melunasi biaya pendaftaran',
			];
		}

		$old = $this->getRate();

		if ($nominal != $old) {
			return [
				'status' => 400,
				'message' => 'Nominal yang diinput tidak valid',
			];
		}

		$invoice = $this->idGenerator();
		$this->db->insert('payment_registration', [
			'id' => $invoice,
			'student_id' => $id,
			'bill' => $old,
			'amount' => $nominal,
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
			$this->db->insert('payment_registration_detail', [
				'payment_registration_id' => $invoice,
				'account_id' => $item->account_id,
				'amount' => (int)$item->amount
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
		$getPackage = $this->db->get_where('payment_registration', [
			'id' => $package
		])->row_object();
		$id = $getPackage->student_id;
		$student = $this->db->get_where('students', ['id' => $id])->row_object();

		$this->db->select('a.*, b.name')->from('payment_registration_detail AS a');
		$this->db->join('payment_account AS b', 'a.account_id = b.id');
		$this->db->where('a.payment_registration_id', $package);
		$detail = $this->db->order_by('a.account_id', 'ASC')->get()->result_object();

		return [$getPackage, $student, $detail];
	}

}
