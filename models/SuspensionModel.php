<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuspensionModel extends CI_Model
{
	public function loadSuspension()
	{
		$period = $this->dm->getperiod();
		$name = $this->input->post('name', true);
		$status = $this->input->post('status', true);

		$this->db->select('a.*, b.name, b.domicile, b.village, b.city, b.class, b.level, b.class_of_formal, b.level_of_formal')->from('suspensions AS a');
		$this->db->join('students AS b', 'b.id = a.student_id');
		$this->db->where('a.period', $period);
		if ($name !== '') {
			$this->db->like('b.name', $name);
		}
		if ($status !== '') {
			$this->db->where('a.status', $status);
		}
		$result = $this->db->order_by('a.created_at DESC, a.status DESC, a.expired_at ASC')->get();

		return [
			$result->num_rows(),
			$result->result_object()
		];
	}

	public function doActive()
	{
		$id = $this->input->post('id', true);
		$term = $this->input->post('term', true);

		//GET ID
		$suspension = $this->db->get_where('suspensions', ['id' => $id])->row_object();
		if ($id === '' || !$suspension) {
			return [
				'status' => 400,
				'message' => 'Data skorsing tidak valid'
			];
		}

		if ($term === '') {
			return [
				'status' => 400,
				'message' => 'Masa skorsing belum dipilih'
			];
		}

		if ($suspension->status === 'ACTIVE') {
			return [
				'status' => 400,
				'message' => 'Skorsing ini sudah diaktfikan sebelumnya'
			];
		}

		if ($suspension->status === 'DONE') {
			return [
				'status' => 400,
				'message' => 'Skorsing ini sudah diselesaikan sebelumnya'
			];
		}

		$this->db->where('id', $id)->update('suspensions', [
			'actived_at' => date('Y-m-d H:i:s'),
			'expired_at' => date('Y-m-d H:i:s', strtotime("+$term days")),
			'status' => 'ACTIVE'
		]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 500,
				'message' => 'Gagal menyimpan data. Coba muat ulang halaman'
			];
		}

		return [
			'status' => 200,
			'message' => 'Skorsing berhasil diaktifkan'
		];
	}

	public function doDone()
	{
		$id = $this->input->post('id', true);

		//GET ID
		$suspension = $this->db->get_where('suspensions', ['id' => $id])->row_object();
		if ($id === '' || !$suspension) {
			return [
				'status' => 400,
				'message' => 'Data skorsing tidak valid'
			];
		}

		if ($suspension->status === 'INACTIVE') {
			return [
				'status' => 400,
				'message' => 'Skorsing ini belum diaktfikan sebelumnya'
			];
		}

		if ($suspension->status === 'DONE') {
			return [
				'status' => 400,
				'message' => 'Skorsing ini sudah diselesaikan sebelumnya'
			];
		}

		$this->db->where('id', $id)->update('suspensions', ['status' => 'DONE']);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 500,
				'message' => 'Gagal menyimpan data. Coba muat ulang halaman'
			];
		}

		return [
			'status' => 200,
			'message' => 'Skorsing berhasil diselesaikan'
		];
	}

	public function doCustom()
	{
		$id = $this->input->post('id', true);
		$custom = (int)$this->input->post('custom', true);

		if (empty($custom)) {
			return [
				'status' => 400,
				'message' => 'Pastikan jumlah hari telah diisi'
			];
		}

		$getSuspension = $this->db->get_where('suspensions', ['id' => $id])->row_object();
		if (!$getSuspension) {
			return [
				'status' => 400,
				'message' => 'Data skorsing tidak valid'
			];
		}

		$status = $getSuspension->status;
		if ($status === 'INACTIVE') {
			return [
				'status' => 400,
				'message' => 'Data skorsing belum diaktifkan'
			];
		}

		if ($status === 'DONE') {
			return [
				'status' => 400,
				'message' => 'Skorsing sudah diselesaikan sebelumnya'
			];
		}


		if ($custom > 0) {
			$save = "+$custom days";
			$message = 'ditambah';
		}else{
			$save = "$custom days";
			$message = 'dikurangi';
		}

		$this->db->where('id', $id)->update('suspensions', [
			'expired_at' => date('Y-m-d H:i:s', strtotime($save, strtotime($getSuspension->expired_at)))
		]);

		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Server gagal menyimpan'
			];
		}

		return [
			'status' => 200,
			'message' => $message
		];

	}

	public function autoDoDone()
	{
		$period = $this->dm->getperiod();
		$result = $this->db->get_where('suspensions', [
			'period' => $period
		])->result_object();

		if ($result) {
			$rows = 0;
			foreach ($result as $item) {
				$expired = $item->expired_at;
				if (date('Y-m-d H:i:s') >= $expired) {
					$this->db->where('id', $item->id)->update('suspensions', [
						'status' => 'DONE'
					]);
				}

				if ($this->db->affected_rows() > 0) {
					$rows++;
				}
			}

			return $rows;
		}

		return 0;
	}
}
