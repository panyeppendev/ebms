<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DistributionModel extends CI_Model
{
	public function distributions()
	{
		$date = $this->input->post('date', true);
		$category = $this->input->post('category', true);
		$name = $this->input->post('name', true);

		if ($category == 'BREAKFAST') {
			$now = date('Y-m-d', strtotime($date.'+1 day'));
		}else{
			$now = $date;
		}

		$this->db->select('a.amount, a.id, b.name, b.village, b.city, b.domicile, b.class, b.level, b.class_of_formal, b.level_of_formal')->from('transactions as a');
		$this->db->join('students as b', 'b.id = a.student_id')->where([
			'a.created_at' => $now, 'a.status' => $category
		]);
		if ($name) {
			$this->db->like('b.name', $name);
		}
		return $this->db->order_by('a.id', 'ASC')->get()->result_object();
	}
	public function setting()
	{
		return $this->db->get_where('set_daily', ['status' => 'OPEN'])->row_object();
	}

	public function dailySetting()
	{
		$user = $this->session->userdata('user_id');
		return $this->db->get_where('set_transaction_daily', ['user_id' => $user])->row_object();
	}

	public function set()
	{
		$user = $this->session->userdata('user_id');
		$table = $this->input->post('table', true);
		$category = $this->input->post('category', true);

		$this->db->where('user_id', $user)->delete('set_transaction_daily');
		$this->db->insert('set_transaction_daily', [
			'table_name' => $table,
			'category' => $category,
			'user_id' => $user
		]);

		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal menyimpan pengaturan'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function checkCard()
	{
		$card = str_replace('_', '', $this->input->post('card', true));

		$setting = $this->setting();
		$dailySetting = $this->dailySetting();
		if (!$setting) {
			return [
				'status' => 500,
				'message' => 'Transaksi hari ini belum dibuka'
			];
		}

		if (!$dailySetting) {
			return [
				'status' => 500,
				'message' => 'Jenis distribusi belum diatur'
			];
		}

		$date = $setting->created_at;
		$table = $dailySetting->table_name;
		$category = $dailySetting->category;
		$user = $dailySetting->user_id;

		if ($category == 'FASTING') {
			$created = date('Y-m-d', strtotime($date.'+1 day'));
		}else{
			$created = $date;
		}

		$account = $this->account($table);
		if (!$account) {
			return [
				'status' => 500,
				'message' => 'Rekening pencairan belum diatur'
			];
		}
		$accountId = $account->account_id;

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
				'message' => 'Santri ini tidak memiliki paket aktif'
			];
		}
		$purchaseId = $checkPurchase->id;
		$packageId = $checkPurchase->package_id;
		$packageName = $checkPurchase->package_name;

		$checkExpenditure = $this->db->get_where('expenditures', [
			'purchase_id' => $purchaseId, 'account_id' => $accountId
		])->row_object();
		if (!$checkExpenditure) {
			return [
				'status' => 500,
				'message' => 'Saldo distribusi tidak tersedia'
			];
		}

		$checkTransaction = $this->db->select_sum('nominal', 'total')->from('transactions')->where([
			'student_id' => $nis, 'account_id' => $accountId
		])->get()->row_object();
		if ($checkTransaction) {
			$debit = $checkTransaction->total;
		}else{
			$debit = 0;
		}

		$residual = $checkExpenditure->nominal - $debit;

		$total = $this->getBalance($purchaseId, $created, $category, $packageId, $accountId);
		if ($residual < $total) {
			return [
				'status' => 500,
				'message' => 'Saldo distribusi tidak tersedia'
			];
		}

		return [
			'status' => 200,
			'nominal' => $total,
			'nis' => $nis,
			'purchase' => $purchaseId,
			'package' => $packageName,
			'account' => $accountId,
			'date' => $created,
			'category' => $category
		];
	}
	public function account($table)
	{
		return $this->db->get($table)->row_object();
	}

	public function getBalance($id, $date, $status, $package, $account)
	{
		$check = $this->db->get_where('transactions', [
			'purchase_id' => $id, 'status' => $status, 'created_at' => $date
		])->row_object();
		if ($check){
			return 0;
		}

		$limit = $this->db->get_where('package_limit', [
			'package_id' => $package, 'account_id' => $account
		])->row_object();

		if (!$limit) {
			return 0;
		}

		return $limit->nominal;

	}
	public function getData()
	{
		$nis = $this->input->post('nis', true);
		$package = $this->input->post('package', true);

		$name = '';
		$address = '';
		$domicile = '';
		$diniyah = '';
		$formal = '';

		$student = $this->db->get_where('students', ['id' => $nis])->row_object();

		if ($student) {
			$name = $student->name;
			$address = $student->village.', '.str_replace(['Kota ', 'Kabupaten '], '', $student->city);
			$domicile = $student->domicile;
			$diniyah = $student->class.' - '.$student->level;
			$formal = $student->class_of_formal.' - '.$student->level_of_formal;
		}

		return [
			'nis' => $nis,
			'name' => $name,
			'address' => $address,
			'domicile' => $domicile,
			'diniyah' => $diniyah,
			'formal' => $formal,
			'package' => $package
		];
	}



	public function store()
	{
		$date = $this->input->post('date', true);
		$nis = $this->input->post('nis', true);
		$purchase = $this->input->post('purchase', true);
		$account = $this->input->post('account', true);
		$nominal = $this->input->post('nominal', true);
		$status = $this->input->post('status', true);

		if ($nominal == '' || $nominal <= 0 || $date == '' || $nis == '' || $purchase == '' || $account == '') {
			return [
				'status' => 400,
				'message' => 'Terjadi kelasahan. Coba refresh halaman'
			];
		}

		$this->db->insert('transactions', [
			'student_id' => $nis,
			'purchase_id' => $purchase,
			'account_id' => $account,
			'role_id' => $this->session->userdata('role_id'),
			'amount' => $nominal,
			'created_at' => $date,
			'status' => $status
		]);

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function destroy()
	{
		$id = $this->input->post('id', true);
		$this->db->where('id', $id)->delete('transactions');
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
