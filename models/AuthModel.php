<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthModel extends CI_Model
{
	public function cekUser($username)
	{
		return $this->db->get_where('users', ['username' => $username])->row_object();
	}
    public function login()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        $cekUser = $this->cekUser($username);
		if (!$cekUser) {
			return [
				'status' => 500,
				'message' => 'Kombinasi username dan password tidak valid'
			];
		}

		$passwordDB = $cekUser->password;
		if (!password_verify($password, $passwordDB)) {
			return [
				'status' => 500,
				'message' => 'Kombinasi username dan password tidak valid'
			];
		}

		$roleUser = $this->roleUser($cekUser->id);
		if (!$roleUser) {
			return [
				'status' => 500,
				'message' => 'Pengguna ini belum punya jabatan'
			];
		}

		$currentRole = $this->getCurrentRole($cekUser->current_role, $cekUser->id);
		$data = [
			'user_id' => $cekUser->id,
			'username' => $cekUser->username,
			'role' => $currentRole[0],
			'role_id' => $currentRole[1],
			'name' => $cekUser->name,
		];
		$this->session->set_userdata($data);
		return [
			'status' => 200,
			'message' => 'Login sukses'
		];
    }

	public function getCurrentRole($currentRole, $user)
	{
		if ($currentRole) {
			$result = $this->db->get_where('roles', ['id' => $currentRole])->row_object();
			return [$result->name, $currentRole];
		}

		$role = $this->roleUser($user);
		$this->db->where('id', $user)->update('users', [
			'current_role' => $role->role_id
		]);
		return [$role->name, $role->role_id];
	}

	public function roleUser($id)
	{
		$this->db->select('a.role_id, b.name')->from('role_user as a');
		$this->db->join('roles as b', 'b.id = a.role_id');
		$this->db->where('a.user_id', $id);
		return $this->db->order_by('a.id', 'ASC')->get()->row_object();
	}

	public function switchRole($roleId)
	{
		$user_id = $this->session->userdata('user_id');
		//Buang session
		$this->session->unset_userdata('role');
		$this->session->unset_userdata('role_id');

		//Update role di tabel user
		$this->db->where('id', $user_id)->update('users', [
			'current_role' => $roleId
		]);

		//Ambil data lengkap role
		$getRole = $this->db->get_where('roles', ['id' => $roleId])->row_object();
		$data = [
			'role' => $getRole->name,
			'role_id' => $getRole->id
		];
		$this->session->set_userdata($data);

		return [
			'status' => 200,
			'message' => 'Success'
		];
	}


}
