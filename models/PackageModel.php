<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PackageModel extends CI_Model
{
	public function accounts()
	{
		$category = $this->input->post('category', true);
		$this->db->select('*')->from('accounts');
		if ($category) {
			$this->db->where('category', $category);
		}

		return $this->db->order_by('category ASC, created_at ASC')->get()->result_object();
	}
	public function save()
	{
		$category = $this->input->post('category', true);
		$name = $this->input->post('name', true);
		if ($category == '' || $name === '') {
			return [
				'status' => 500,
				'message' => 'Kategori atau Nama harus diisi'
			];
		}

		$this->db->insert('accounts', [
			'name' => strtoupper($name),
			'category' => $category,
			'created_at' => date('Y-m-d H:i:s')
		]);
		$save = $this->db->affected_rows();

		if ($save <= 0) {
			return [
				'status' => 500,
				'message' => 'Server gagal menyimpan'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}
}
