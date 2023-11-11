<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseModel extends CI_Model
{
	public function purchases()
	{
		$filter = $this->input->post('filter', true);
		$name = $this->input->post('name', true);

		$this->db->select('a.id as purchase, a.student_id as id, a.amount, a.status, a.package_name as package, c.name, c.domicile, c.village, c.city')->from('purchases as a');
		$this->db->join('students as c', 'c.id = a.student_id');
		if ($name) {
			$this->db->like('c.name', $name);
		}

		if ($filter) {
			$this->db->where('a.status', $filter);
		}

		return $this->db->get()->result_object();
	}

	public function purchasebyid()
	{
		$nis = str_replace('_', '', $this->input->post('nis', true));

		$this->db->select('a.id, a.student_id, a.amount, a.status, a.package_name as package, c.name, c.domicile, c.village, c.city')->from('purchases as a');
		$this->db->join('students as c', 'c.id = a.student_id');
		$this->db->where(['a.status !=' => 'DONE', 'a.student_id' => $nis]);

		return $this->db->get()->result_object();
	}
    public function packages()
    {
        return $this->db->order_by('name', 'ASC')->get('packages')->result_object();
    }

	public function addons()
	{
		return $this->db->get_where('accounts', [
			'category' => 'ADDON', 'status' => 'ACTIVE'
		])->result_object();
	}

    public function save()
    {
        $nis = str_replace('_', '', $this->input->post('nis', true));
		$package = $this->input->post('package', true);
		$addon = $this->input->post('addon', true);

		if ($nis == '' || $package == '') {
			return [
				'status' => false,
				'message' => 'Pastikan nis dan paket sudah dipilih'
			];
		}

		$student = $this->db->get_where('students', [
			'id' => $nis, 'status' => 'AKTIF'
		])->row_object();

		if (!$student) {
			return [
				'status' => false,
				'message' => 'Data santri tidak ditemukan'
			];
		}

		$checkPackage = $this->db->get_where('packages', ['id' => $package])->row_object();
		if (!$checkPackage) {
			return [
				'status' => false,
				'message' => 'Data paket tidak valid'
			];
		}

		$this->db->where(['student_id' => $nis, 'user' => $this->session->userdata('user_id')])->delete('purchase_temp');

		$this->saveAddon($nis, $addon);
		$this->savePackage($nis, $package);
		$this->db->select('a.*, b.name')->from('purchase_temp as a')->join('accounts as b', 'b.id = a.account_id');
		$purchases = $this->db->where([
			'a.student_id' => $nis, 'a.user' => $this->session->userdata('user_id')
		])->order_by('b.category', 'ASC')->get()->result_object();

        return [
			'status' => true,
			'student' => $student,
			'purchases' => $purchases,
			'package' => $package,
			'package_name' => $checkPackage->name
		];
    }

	public function savePackage($nis, $package)
	{
		$detail = $this->db->get_where('package_detail', ['package_id' => $package])->result_object();
		if ($detail) {
			foreach ($detail as $d) {
				$this->db->insert('purchase_temp', [
					'student_id' => $nis,
					'account_id' => $d->account_id,
					'nominal' => $d->nominal,
					'user' => $this->session->userdata('user_id')
				]);
			}
		}
	}

	public function saveAddon($nis, $addons)
	{
		if ($addons) {
			foreach ($addons as $key => $value) {
				$this->db->insert('purchase_temp', [
					'student_id' => $nis,
					'account_id' => $key,
					'nominal' => $value,
					'user' => $this->session->userdata('user_id')
				]);
			}
		}
	}

	public function store()
	{
		$nis = $this->input->post('nis', true);
		$package = $this->input->post('packageId', true);
		$packageName = $this->input->post('packageName', true);
		$amount = $this->input->post('amount', true);
		$id = $this->idGenerator();
		$user = $this->session->userdata('user_id');

		$data = $this->db->get_where('purchase_temp', [
			'student_id' => $nis,
			'user' => $user
		])->result_object();

		if ($data) {
			$this->db->insert('purchases', [
				'id' => $id,
				'student_id' => $nis,
				'package_id' => $package,
				'package_name' => $packageName,
				'amount' => $amount,
				'created_at' => date('Y-m-d H:i:s'),
				'user_id' => $user,
				'status' => 'INACTIVE'
			]);

			foreach ($data as $d) {
				$this->db->insert('purchase_detail', [
					'account_id' => $d->account_id,
					'nominal' => $d->nominal,
					'purchase_id' => $id
				]);
			}
		}

		$this->db->where([
			'student_id' => $nis,
			'user' => $user
		])->delete('purchase_temp');

		return [
			'status' => true,
			'message' => $id
		];

	}

	public function idGenerator()
	{
		return date('Y').date('m').date('d').mt_rand(100000, 999999);
	}

	public function invoice()
	{
		$id = $this->input->post('id', true);

		$this->db->select('a.id, a.created_at, a.amount, a.package_name as package, c.name, c.domicile, c.class, c.level, c.class_of_formal, c.level_of_formal, c.village, c.city, d.name as user');
		$this->db->from('purchases as a')->join('students as c', 'c.id = a.student_id');
		$data = $this->db->join('users as d', 'd.id = a.user_id')->where('a.id', $id)->get()->row_object();

		$this->db->select('a.nominal, b.name')->from('purchase_detail as a')->join('accounts as b', 'b.id = a.account_id');
		$package = $this->db->where(['a.purchase_id' => $id, 'b.category' => 'PACKAGE'])->order_by('b.id', 'ASC')->get()->result_object();

		$this->db->select('a.nominal, b.name')->from('purchase_detail as a')->join('accounts as b', 'b.id = a.account_id');
		$addon = $this->db->where(['a.purchase_id' => $id, 'b.category' => 'ADDON'])->order_by('b.id', 'ASC')->get()->result_object();

		return [$data, $package, $addon];
	}

	public function activate()
	{
		$id = $this->input->post('id', true);
		$check = $this->db->get_where('purchases', ['id' => $id])->row_object();
		if (!$check) {
			return [
				'status' => 400,
				'message' => 'Data paket tidak valid'
			];
		}

		$student = $check->student_id;
		$checkActiveOther = $this->db->get_where('purchases', [
			'student_id' => $student, 'status' => 'ACTIVE'
		])->row_object();
		if ($checkActiveOther) {
			return [
				'status' => 400,
				'message' => 'Pastikan tidak ada paket aktif'
			];
		}

		$package = $check->package_id;
		$getPackage = $this->db->get_where('packages', ['id' => $package])->row_object();
		if ($getPackage) {
			$packageName = $getPackage->name;
		}else{
			$packageName = '';
		}

		$income = $this->setIncome($id, $check->student_id, $packageName, $package);
		if ($income <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal saat set income'
			];
		}

		$expenditure = $this->setExpenditure($id, $check->student_id, $packageName, $package);
		if ($expenditure <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal saat set expenditure'
			];
		}

		$this->db->where('id', $id)->update('purchases', ['status' => 'ACTIVE']);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal saat update purchase'
			];
		}


		return [
			'status' => 200,
			'message' => 'Satu paket berhasil diaktifkan'
		];
	}

	public function setIncome($id, $student, $package, $packageId)
	{
		$data = $this->db->get_where('purchase_detail', ['purchase_id' => $id])->result_object();

		$rows = 0;
		if ($data) {
			foreach ($data as $d) {
				$account = $d->account_id;
				$this->db->insert('incomes', [
					'purchase_id' => $id,
					'package_name' => $package,
					'account_id' => $account,
					'student_id' => $student,
					'nominal' => $d->nominal,
					'created_at' => date('Y-m-d'),
					'caption' => 'AKTIVASI PAKET | '.$packageId
				]);

				if ($this->db->affected_rows() > 0) {
					$rows++;
				}
			}
		}

		return $rows;
	}

	public function setExpenditure($id, $student, $package, $packageId)
	{
		$this->db->select('a.nominal, b.category, b.id')->from('purchase_detail as a');
		$this->db->join('accounts as b', 'b.id = a.account_id');
		$data = $this->db->where(['a.purchase_id' => $id, 'b.category' => 'PACKAGE'])->get()->result_object();

		$rows = 0;
		if ($data) {
			foreach ($data as $d) {
				$account = $d->id;
				$nominal = $this->getLimit($account, $packageId);
				if ($nominal > 0) {
					$this->db->insert('expenditures', [
						'purchase_id' => $id,
						'package_name' => $package,
						'account_id' => $account,
						'student_id' => $student,
						'nominal' => $nominal,
						'created_at' => date('Y-m-d'),
						'caption' => 'AKTIVASI PAKET '.$id
					]);
					if ($this->db->affected_rows() > 0) {
						$rows++;
					}
				}
			}
		}

		return $rows;
	}

	public function getLimit($id, $package)
	{
		$data = $this->db->get_where('package_limit', [
			'account_id' => $id, 'package_id' => $package
		])->row_object();

		$nominal = 0;

		if ($data) {
			return ($data->nominal * $data->qty) * 30;
		}

		return $nominal;
	}

	public function finished()
	{
		$id = $this->input->post('id', true);
		$purchase = $this->db->get_where('purchases', ['id' => $id])->row_object();
		if (!$purchase) {
			return [
				'status' => 400,
				'message' => 'Data paket tidak valid'
			];
		}
		$this->setDisbursementRecap($id);
		$this->setTransactionRecap($id);
		$this->db->where('id', $id)->update('purchases', ['status' => 'DONE']);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal memperbarui data'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function destroy()
	{
		$id = $this->input->post('id', true);
		$purchase = $this->db->get_where('purchases', [
			'id' => $id
		])->row_object();

		if (!$purchase) {
			return [
				'status' => 400,
				'message' => 'Data tidak valid'
			];
		}

		$status = $purchase->status;
		if ($status != 'INACTIVE') {
			return [
				'status' => 400,
				'message' => 'Transaksi ini tidak bisa dihapus'
			];
		}

		$this->db->where('id', $id)->delete('purchases');
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal saat menghapus data'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function setDisbursementRecap($id)
	{
		$this->db->select('SUM(amount) as total, student_id as student, account_id as account, role_id as role');
		$this->db->from('disbursements')->where('purchase_id', $id)->group_by(['account_id', 'role_id']);
		$result = $this->db->get()->result_object();
		$data = [];
		if ($result) {
			foreach ($result as $item) {
				$data[] = [
					'student_id' => $item->student,
					'purchase_id' => $id,
					'account_id' => $item->account,
					'role_id' => $item->role,
					'nominal' => $item->total,
					'created_at' => date('Y-m-d')
				];
			}
			$this->db->insert_batch('distributions', $data);
		}
	}

	public function setTransactionRecap($id)
	{
		$this->db->select('SUM(amount) as total, student_id as student, purchase_id as purchase, account_id as account, role_id as role');
		$this->db->from('transactions')->where('purchase_id', $id)->group_by(['account_id', 'role_id']);
		$result = $this->db->get()->result_object();
		$data = [];
		if ($result) {
			foreach ($result as $item) {
				$data[] = [
					'student_id' => $item->student,
					'purchase_id' => $id,
					'account_id' => $item->account,
					'role_id' => $item->role,
					'nominal' => $item->total,
					'created_at' => date('Y-m-d')
				];
			}
			$this->db->insert_batch('distributions', $data);
		}
	}

	public function reset()
	{
		$this->db->empty_table('expenditures');
		$purchases = $this->db->get_where('purchases', [
			'status !=' => 'INACTIVE'
		])->result_object();
		if ($purchases) {
			foreach ($purchases as $purchase) {
				$id = $purchase->id;
				$package = $purchase->package_id;
				$packageName = $purchase->package_name;
				$dateTime = new DateTime($purchase->created_at);
				$detail = $this->db->get_where('purchase_detail', ['purchase_id' => $id])->result_object();
				if ($detail) {
					foreach ($detail as $item) {
						$account = $item->account_id;
						$nominal = $this->getLimit($account, $package);
						if ($nominal > 0) {
							$this->db->insert('expenditures', [
								'purchase_id' => $id,
								'package_name' => $packageName,
								'account_id' => $account,
								'student_id' => $purchase->student_id,
								'nominal' => $nominal,
								'created_at' => $dateTime->format('Y-m-d'),
								'caption' => 'AKTIVASI PAKET '.$id
							]);
						}
					}
				}
			}
		}
	}
}
