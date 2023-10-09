<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PackageModel extends CI_Model
{
	public function packages()
	{
		return $this->db->order_by('name', 'ASC')->get('packages')->result_object();
	}

	public function packageById()
	{
		$id = $this->input->post('id', true);

		$this->db->select('a.*, b.name')->from('package_detail as a');
		$this->db->join('accounts as b', 'b.id = a.account_id');
		return $this->db->where('a.package_id', $id)->order_by('b.id', 'ASC')->get()->result_object();
	}
	public function accounts()
	{
		$this->db->select('*')->from('accounts')->where('category', 'PACKAGE');

		return $this->db->order_by('id')->get()->result_object();
	}

	public function store()
	{
		$id = $this->idGenerator();

		$name = $this->input->post('name', true);
		$account = $this->input->post('account', true);

		$this->db->insert('packages', [
			'id' => $id,
			'name' => strtoupper($name),
			'created_at' => date('Y-m-d H:i:s')
		]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => false,
				'message' => 'Opp.. Data paket gagal disimpan'
			];
		}

		$amount = 0;
		$rows = 0;
		if ($account) {
			foreach ($account as $key => $value) {
				$nominal = str_replace('.', '', $value);
				if ($nominal > 0) {
					$amount += $nominal;
					$this->db->insert('package_detail', [
						'package_id' => $id,
						'account_id' => $key,
						'nominal' => $nominal
					]);
					if ($this->db->affected_rows() > 0) {
						$rows++;
					}
				}
			}
		}

		if ($rows <= 0) {
			$this->db->where('id', $id)->delete('packages');
			return [
				'status' => false,
				'message' => 'Opp.. Gagal menyimpan detil paket'
			];
		}

		$this->db->where('id', $id)->update('packages', ['amount' => $amount]);

		return [
			'status' => true,
			'message' => $id
		];

	}

	public function idGenerator()
	{
		return date('Y').date('m').date('d').mt_rand(10000, 99999);
	}

	public function package($id)
	{
		$package = $this->db->get_where('packages', ['id' => $id])->row_object();
		$details = [];
		if ($package) {
			$this->db->select('a.*, b.name')->from('package_detail as a')->join('accounts as b', 'b.id = a.account_id');
			$detail = $this->db->where('a.package_id', $id)->order_by('b.id', 'ASC')->get()->result_object();
			if ($detail) {
				foreach ($detail as $d) {
					$details[] = [
						'id' => $d->id,
						'name' => $d->name,
						'nominal' => $d->nominal
					];
				}
			}

			return [
				'status' => true,
				'id' => $package->id,
				'name' => $package->name,
				'amount' => $package->amount,
				'detail' => $details
			];
		}

		return [
			'status' => false,
			'id' => $id
		];
	}

	public function update()
	{
		$id = $this->input->post('id', true);
		$account = $this->input->post('account', true);

		$amount = 0;
		$rows = 0;
		if ($account) {
			foreach ($account as $key => $value) {
				$nominal = str_replace('.', '', $value);
				if ($nominal > 0) {
					$amount += $nominal;
					$this->db->where('id', $key)->update('package_detail', [
						'nominal' => $nominal
					]);
					if ($this->db->affected_rows() > 0) {
						$rows++;
					}
				}
			}
		}

		if ($rows <= 0) {
			return [
				'status' => false,
				'message' => 'Opp.. Tak ada data yang berhasil diubah'
			];
		}

		if ($amount > 0) {
			$this->db->where('id', $id)->update('packages', [
				'amount' => $amount,
				'updated_at' => date('Y-m-d H:i:s')
			]);
			if ($this->db->affected_rows() <= 0) {
				return [
					'status' => false,
					'message' => 'Opp.. Data paket gagal diubah'
				];
			}
		}

		return [
			'status' => true,
			'message' => $id
		];

	}

	public function destroy()
	{
		$id = $this->input->post('id', true);
		$check = $this->db->get_where('packages', ['id' => $id])->row_object();
		if (!$check) {
			return [
				'status' => false,
				'message' => 'Data paket tidak valid'
			];
		}

		$this->db->where('id', $id)->delete('packages');
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => false,
				'message' => 'Opp.. Gagal membuang data'
			];
		}

		return [
			'status' => true,
			'message' => $id
		];
	}
}
