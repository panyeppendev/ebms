<?php
defined('BASEPATH') or exit('No direct script access allowec');

class ConstitutionModel extends CI_Model
{
	public function setConstitutionDefault()
	{
		$data = [
			[
				'id' => 'M-10001',
				'name' => 'Melakukan pelanggaran ringan sebanyak tujuh kali',
				'type' => 'PROHIBITION',
				'clause' => 1,
				'category' => 'MEDIUM'
			],
			[
				'id' => 'H-10001',
				'name' => 'Melakukan pelanggaran sedang sebanyak lima kali',
				'type' => 'PROHIBITION',
				'clause' => 1,
				'category' => 'HIGH'
			],
			[
				'id' => 'T-10001',
				'name' => 'Melakukan pelanggaran berat sebanyak empat kali',
				'type' => 'PROHIBITION',
				'clause' => 1,
				'category' => 'TOP'
			]
		];
		foreach ($data as $item) {
			$check = $this->db->get_where('constitutions', ['id' => $item['id']])->num_rows();
			if ($check <= 0) {
				$this->db->insert('constitutions', [
					'id' => $item['id'],
					'name' => $item['name'],
					'type' => $item['type'],
					'category' => $item['category'],
					'clause' => $item['clause'],
					'created_at' => date('Y-m-d H:i:s')
				]);
			}
		}
	}

	public function loadData()
	{
		$type = $this->input->post('type', true);
		$category = $this->input->post('category', true);

		$this->db->select('*')->from('constitutions');

		if ($type !== '') {
			$this->db->where('type', $type);
		}

		if ($category !== '') {
			$this->db->where('category', $category);
		}

		return $this->db->order_by('type ASC, category ASC, clause ASC')->get()->result_object();
	}

	public function save()
	{
		$id = $this->input->post('id', true);
		$type = $this->input->post('type', true);
		$category = $this->input->post('category', true);
		$clause = $this->input->post('clause', true);
		$name = $this->input->post('name', true);
		if ($category === '' || $type === '' || $name === '' || $clause === '') {
			return [
				'status' => 400,
				'message' => 'Pastikan semua sudah dipilih dan diisi'
			];
		}

		if (in_array($id, ['M-10001', 'H-10001', 'T-10001'])) {
			return [
				'status' => 400,
				'message' => 'Undang-undang bawaan tidak bisa diedit'
			];
		}

		if ($id === '0') {
			$getConstitution = $this->db->get_where('constitutions', [
				'type' => $type, 'category' => $category, 'clause' => $clause
			])->num_rows();
		}else{
			$getConstitution = $this->db->get_where('constitutions', [
				'id !=' => $id, 'type' => $type, 'category' => $category, 'clause' => $clause
			])->num_rows();
		}
		if ($getConstitution > 0) {
			return [
				'status' => 400,
				'message' => 'Bunyi ayat sudah ada sebelumnya'
			];
		}

		$data = [
			'name' => ucfirst($name),
			'type' => $type,
			'category' => $category,
			'clause' => $clause,
			'created_at' => date('Y-m-d H:i:s')
		];
		if ($id !== '0') {
			$this->db->where('id', $id)->update('constitutions', $data);
			$message = 'Data berhasil diubah';
		} else {
			$id = substr($category, 0, 1).'-'.mt_rand(10000, 99999);
			$data = array_merge(['id' => $id], $data);
			$this->db->insert('constitutions', $data);
			$message = 'Data berhasil ditambahkan';
		}
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal menyimpan data'
			];
		}

		return [
			'status' => 200,
			'message' => $message
		];
	}

	public function getById()
	{
		$id = $this->input->post('id', true);
		$result = $this->db->get_where('constitutions', ['id' => $id])->row_object();
		if (!$result) {
			return [
				'status' => 400,
				'message' => 'Data tidak ditemukan'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses',
			'id' => $id,
			'type' => $result->type,
			'category' => $result->category,
			'clause' => $result->clause,
			'name' => $result->name
		];
	}

}
