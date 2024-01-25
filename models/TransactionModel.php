<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransactionModel extends CI_Model
{
	public function transactions()
	{
		$role = $this->session->userdata('role_id');
		$date = $this->input->post('date', true);
		$name = $this->input->post('name', true);
		$this->db->select('a.amount, a.id, b.name, b.village, b.city, b.domicile, b.class, b.level, b.class_of_formal, b.level_of_formal')->from('disbursements as a');
		$this->db->join('students as b', 'b.id = a.student_id')->where(['a.created_at' => $date, 'role_id' => $role]);
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
		$role = $this->session->userdata('role_id');
		$date = $this->input->post('date', true);
		$data = $this->db->select('SUM(amount) as total')->from('disbursements')->where([
			'created_at' => $date, 'role_id' => $role
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
		$disbursement = $this->getDisbursement($nis, $setting->created_at, 10);
		$pocket = ($balance[0] + $balance[1]) - $disbursement;

		$depositCredit = $this->getAmount($nis, 'deposit_credit', '');
		$depositDebit = $this->getAmount($nis, 'deposit_debit', '');
		$deposit = $depositCredit - $depositDebit;

		return [
			'status' => 200,
			'pocket' => $pocket,
			'deposit' => $deposit,
			'total' => $pocket + $deposit,
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
		$daily = 0;
		$reserved = 0;
		$disbursementCash = 0;
		$disbursementDebit = 0;

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

		$getDisbursementCash = $this->getDisbursement($nis, $setting->created_at, 0);
		if ($getDisbursementCash) {
			$disbursementCash = $getDisbursementCash;
		}

		$getDisbursementDebit = $this->getDisbursement($nis, $setting->created_at, 1);
		if ($getDisbursementDebit) {
			$disbursementDebit = $getDisbursementDebit;
		}

		$disbursement = $disbursementCash + $disbursementDebit;
		$total = ($daily + $reserved) - $disbursement;

		$depositCredit = $this->getAmount($nis, 'deposit_credit', '');
		$depositCash = $this->getAmount($nis, 'deposit_debit', 0);
		$depositDebit = $this->getAmount($nis, 'deposit_debit', 1);
		$balance = $depositCredit - ($depositCash + $depositDebit);

		$grandTotal = $total + $balance;

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
			'disbursement_cash' => $disbursementCash,
			'disbursement_debit' => $disbursementDebit,
			'pocket' => ($daily + $reserved) - $disbursement,
			'total' => $daily - $disbursement,
			'deposit_credit' => $depositCredit,
			'deposit_cash' => $depositCash,
			'deposit_debit' => $depositDebit,
			'deposit_balance' => $balance,
			'grand_total' => $grandTotal
		];
	}

	public function getAmount($nis, $table, $status)
	{
		$this->db->select_sum('amount', 'credit')->where('student_id', $nis);
		if ($table == 'deposit_debit') {
			$this->db->where('status', $status);
		}
		$result = $this->db->get($table)->row_object();
		if ($result) {
			return $result->credit;
		}

		return 0;
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

	public function getDisbursement($nis, $date, $status)
	{
		$this->db->select('SUM(amount) as total')->from('disbursements')->where([
			'student_id' => $nis, 'created_at' => $date
		]);
		if ($status != 10) {
			$this->db->where('status', $status);
		}
		$data = $this->db->get()->row_object();

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
		$pocket = $this->input->post('pocket', true);
		$deposit = $this->input->post('deposit', true);
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

		$residual = $pocket - $nominal;
		if ($residual < 0) {
			$this->db->insert('deposit_debit', [
				'student_id' => $nis,
				'amount' => $nominal - $pocket,
				'role_id' => $this->session->userdata('role_id'),
				'created_at' => $date,
				'status' => 1
			]);
			$nominal = $pocket;
		}

		if ($pocket > 0) {
			$this->db->insert('disbursements', [
				'student_id' => $nis,
				'purchase_id' => $purchase,
				'account_id' => $account,
				'amount' => $nominal,
				'role_id' => $this->session->userdata('role_id'),
				'created_at' => $date,
				'status' => 1
			]);
		}

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

	public function searchCard($name)
	{
		$this->db->select('a.id, b.name, b.village, b.city, b.domicile, b.class, b.level, b.class_of_formal, b.level_of_formal')->from('students as b');
		$this->db->join('cards as a', 'a.student_id = b.id')->where('a.status', 'ACTIVE');
		if ($name != '') {
			$this->db->like('b.name', $name);
		}
		return $this->db->order_by('b.name')->get()->result_object();
	}
}
