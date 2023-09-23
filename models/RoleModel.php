<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoleModel extends CI_Model
{
	public function roles()
	{
		return $this->db->get('roles')->result_object();
	}
	public function save()
	{
		$name = $this->input->post('name', true);
		if ($name === '') {
			return [
				'status' => 400,
				'message' => 'Nama harus diisi'
			];
		}

		$name = $this->input->post('name', true);
		$this->db->insert('roles', ['name' => strtoupper($name)]);
		$save = $this->db->affected_rows();

		if ($save <= 0) {
			return [
				'status' => 500,
				'message' => 'Gagal menyimpan'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
    }
}
