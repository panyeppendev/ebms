<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountModel extends CI_Model
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
		$id = $this->input->post('id', true);
		$category = $this->input->post('category', true);
		$name = $this->input->post('name', true);
		$nominal = $this->input->post('nominal', true);

		if ($category == '' || $name === '') {
			return [
				'status' => 500,
				'message' => 'Kategori atau Nama harus diisi'
			];
		}

		if ($id == '') {
			$this->db->insert('accounts', [
				'name' => strtoupper($name),
				'category' => $category,
				'nominal' => $nominal,
				'created_at' => date('Y-m-d H:i:s'),
				'status' => 'ACTIVE'
			]);
			$message = ['Tidak ada data yang berhasil ditambahkan', 'ditambahkan'];
		}else{
			$this->db->where('id', $id)->update('accounts', [
				'category' => $category,
				'nominal' => $nominal
			]);

			$message = ['Tidak ada data yang berhasil diperbarui', 'diperbarui'];
		}

		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 500,
				'message' => $message[0]
			];
		}

		return [
			'status' => 200,
			'message' => $message[1]
		];
    }

	public function edit($id)
	{
		if (!$id) {
			return [
				'status' => 400,
				'message' => 'ID tidak valid',
				'data' => []
			];
		}

		$data = $this->db->get_where('accounts', ['id' => $id])->row_object();
		if (!$data) {
			return [
				'status' => 400,
				'message' => 'Data tidak ditemukan',
				'data' => []
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses',
			'data' => [
				'id' => $id,
				'category' => $data->category,
				'name' => $data->name,
				'nominal' => $data->nominal,
			]
		];
	}

	public function setStatus()
	{
		$id = $this->input->post('id', true);
		$status = $this->input->post('status', true);
		$this->db->where('id', $id)->update('accounts', ['status' => $status]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => false,
				'message' => 'Server gagal memperbarui status'
			];
		}

		return [
			'status' => true,
			'message' => 'Sukses'
		];
	}
}
