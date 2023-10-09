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

	public function open()
	{
		$date = date('Y-m-d');
		$account = $this->input->post('account', true);
		$check = $this->db->get_where('set_daily', ['created_at' => $date])->row_object();

		if ($check) {
			return [
				'status' => 400,
				'message' => "Tanggal $date sudah diset sebelumnya"
			];
		}

		$this->db->insert('set_daily', [
			'created_at' => $date, 'amount' => 0, 'status' => 'OPEN'
		]);

		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => "Gagal menyimpan pengaturan"
			];
		}

		$this->setLimit($account);
		$this->db->insert('account_pocket', ['account_id' => $account]);

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function setLimit($account)
	{
		$result = $this->db->get_where('purchases', ['status' => 'ACTIVE'])->result_object();
		if ($result) {
			foreach ($result as $item) {
				$package = $item->package_id;
				$student = $item->student_id;
				$limit = $this->getLimit($package, $account);
				$reserved = $this->getReserved($student);
				if ($limit > 0) {
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

	public function close()
	{
		$data = $this->db->get('daily_pocket_limit')->result_object();
		$setting = $this->setting();
		if (!$setting) {
			return [
				'status' => 400,
				'message' => 'Tanggal transaksi belum diatur'
			];
		}

		if ($data) {
			foreach ($data as $d) {
				$total = $d->pocket + $d->reserved;
				$date = $setting->created_at;
				$disbursement = $this->getDisbursement($d->student_id, $date);
				$final = $total - $disbursement;
				if ($final > 0) {
					$this->db->where('student_id', $d->student_id)->delete('reserved_pocket');
					$this->db->insert('reserved_pocket', [
						'student_id' => $d->student_id, 'amount' => $final
					]);
				}
			}
			$this->db->truncate('daily_pocket_limit');
			$this->db->truncate('set_daily');
		}

		return [
			'status' => 200,
			'message' => 'Tarnsaksi berhasil ditutup'
		];
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

}
