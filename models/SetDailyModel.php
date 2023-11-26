<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SetDailyModel extends CI_Model
{
	public function setting()
	{
		return $this->db->get_where('set_daily', ['status' => 'OPEN'])->row_object();
	}

	public function accounts()
	{
		return $this->db->get_where('accounts', [
			'category' => 'PACKAGE', 'status' => 'ACTIVE'
		])->result_object();
	}

	public function store()
	{
		$table = $this->input->post('table', true);
		$account = $this->input->post('account', true);

		if ($table == '' || $account == '') {
			return [
				'status' => 400,
				'message' => 'Pastikan rekening telah dipilih'
			];
		}

		$checkAccount = $this->db->get_where('accounts', ['id' => $account])->row_object();
		if (!$checkAccount) {
			return [
				'status' => 400,
				'message' => 'Rekening tidak valid'
			];
		}

		$this->db->empty_table($table);
		$this->db->insert($table, ['account_id' => $account]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal menyimpan permintaan'
			];
		}

		return [
			'status' => 200,
			'message' => 'Success'
		];
	}
	public function open()
	{
		$date = date('Y-m-d');
		$check = $this->db->get_where('set_daily', ['created_at' => $date])->row_object();

		if ($check) {
			return [
				'status' => 400,
				'message' => "Tanggal $date sudah diset sebelumnya"
			];
		}

		$checkLog = $this->db->get_where('set_daily_log', ['created_at' => $date])->row_object();
		if ($checkLog) {
			return [
				'status' => 400,
				'message' => "Tanggal $date sudah pernah diset"
			];
		}

		$account = $this->checkAccount();
		if (!$account['status']) {
			return [
				'status' => 400,
				'message' => $account['message']
			];
		}

		$this->db->insert('set_daily_log', [
			'created_at' => $date
		]);

		$this->db->insert('set_daily', [
			'created_at' => $date, 'amount' => 0, 'status' => 'OPEN'
		]);

		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => "Gagal menyimpan pengaturan"
			];
		}

		$this->setLimit($account['account']);

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function checkAccount()
	{
		$account = $this->db->get('account_breakfast')->row_object();
		if (!$account) {
			return [
				'status' => false,
				'message' => "Rekening pencairan sarapan belum diatur"
			];
		}
		$account = $this->db->get('account_dpu')->row_object();
		if (!$account) {
			return [
				'status' => false,
				'message' => "Rekening pencairan DPU belum diatur"
			];
		}
		$account = $this->db->get('account_pocket')->row_object();
		if (!$account) {
			return [
				'status' => false,
				'message' => "Rekening pencairan uang saku belum diatur"
			];
		}

		return [
			'status' => true,
			'account' => $account->account_id
		];
	}

	public function setLimit($account)
	{
		$result = $this->db->get_where('purchases', ['status' => 'ACTIVE'])->result_object();
		if ($result) {
			foreach ($result as $item) {
				$package = $item->package_id;
				$student = $item->student_id;
				$getLimit = $this->getLimit($package, $account);
				$getReserved = $this->getReserved($student);
				$limitDaily = $getLimit + $getReserved;
				$balance = $this->getAllBalance($student, $account);

				if ($balance <= 0) {
					$limit = 0;
					$reserved = 0;
				}else{
					if ($balance >= $limitDaily) {
						$limit = $getLimit;
						$reserved = $getReserved;
					}else{
						if ($balance >= $getLimit) {
							$residual = $balance - $getLimit;
							$limit = $getLimit;
							$reserved = ($residual <= 0) ? 0 : $residual;
						}else{
							$limit = $balance;
							$reserved = 0;
						}
					}
				}

				if ($limit > 0 || $reserved > 0) {
					$this->db->insert('daily_pocket_limit', [
						'student_id' => $student,
						'pocket' => $limit,
						'reserved' => $reserved
					]);
				}
			}
		}
	}

	public function getLimit($package, $account)
	{
		$result = $this->db->get_where('package_limit', [
			'package_id' => $package, 'account_id' => $account
		])->row_object();
		if ($result) {
			return $result->nominal * $result->qty;
		}

		return 0;
	}

	public function getReserved($id)
	{
		$result = $this->db->get_where('reserved_pocket', [
			'student_id' => $id
		])->row_object();
		if ($result) {
			return $result->amount;
		}

		return 0;
	}

	public function getAllBalance($student, $account)
	{
		$credit = $this->db->select_sum('nominal', 'total')->from('expenditures')->where([
			'student_id' => $student, 'account_id' => $account
		])->get()->row_object();
		if ($credit) {
			$credit = $credit->total;
		}else{
			$credit = 0;
		}

		$debit = $this->db->select_sum('nominal', 'total')->from('distribution_daily')->where([
			'student_id' => $student, 'account_id' => $account
		])->get()->row_object();
		if ($debit) {
			$debit = $debit->total;
		}else{
			$debit = 0;
		}

		return $credit - $debit;
	}

	public function close()
	{
		$setting = $this->setting();
		if (!$setting) {
			return [
				'status' => 400,
				'message' => 'Tanggal transaksi belum diatur'
			];
		}
		$date = $setting->created_at;

		$this->setPocket($date);
		$this->setDisbursementRecap($date);
		$this->setTransactionRecap($date);

		$this->db->empty_table('set_transaction_daily');

		return [
			'status' => 200,
			'message' => 'Tarnsaksi berhasil ditutup'
		];
	}

	public function setPocket($date)
	{
		$data = $this->db->get('daily_pocket_limit')->result_object();
		if ($data) {
			$this->db->empty_table('reserved_pocket');
			foreach ($data as $d) {
				$total = $d->pocket + $d->reserved;
				$disbursement = $this->getDisbursement($d->student_id, $date);
				$final = $total - $disbursement;
				if ($final > 0) {
					$this->db->insert('reserved_pocket', [
						'student_id' => $d->student_id, 'amount' => $final
					]);

					$this->db->insert('reserved_pocket_daily', [
						'student_id' => $d->student_id,
						'amount' => $final,
						'created_at' => $date
					]);
				}
			}
			$this->db->empty_table('daily_pocket_limit');
			$this->db->empty_table('set_daily');
		}
	}

	public function getDisbursement($id, $date)
	{
		$this->db->select('SUM(amount) as total')->from('disbursements');
		$data = $this->db->where(['student_id' => $id, 'created_at' => $date])->get()->row_object();
		if ($data) {
			return $data->total;
		}

		return 0;
	}

	public function setDisbursementRecap($date)
	{
		$this->db->select('SUM(amount) as total, student_id as student, purchase_id as purchase, account_id as account, role_id as role');
		$this->db->from('disbursements')->where('created_at', $date)->group_by(['student_id', 'account_id', 'role_id']);
		$result = $this->db->get()->result_object();
		$data = [];
		if ($result) {
			foreach ($result as $item) {
				$data[] = [
					'student_id' => $item->student,
					'purchase_id' => $item->purchase,
					'account_id' => $item->account,
					'role_id' => $item->role,
					'nominal' => $item->total,
					'created_at' => $date
				];
			}
			$this->db->insert_batch('distribution_daily', $data);
		}
	}

	public function setTransactionRecap($date)
	{
		$this->db->select('SUM(amount) as total, student_id as student, purchase_id as purchase, account_id as account, role_id as role');
		$this->db->from('transactions')->where('created_at', $date)->group_by(['student_id', 'account_id', 'role_id']);
		$result = $this->db->get()->result_object();
		$data = [];
		if ($result) {
			foreach ($result as $item) {
				$data[] = [
					'student_id' => $item->student,
					'purchase_id' => $item->purchase,
					'account_id' => $item->account,
					'role_id' => $item->role,
					'nominal' => $item->total,
					'created_at' => $date
				];
			}
			$this->db->insert_batch('distribution_daily', $data);
		}
	}

	public function reset()
	{
		$setting = $this->setting();
		if ($setting) {
			$this->db->where('created_at', $setting->created_at)->delete('reserved_pocket_daily');
		}
		$this->db->empty_table('reserved_pocket');
		$this->db->update('daily_pocket_limit', ['reserved' => 0]);
	}

}
