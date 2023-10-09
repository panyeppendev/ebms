<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{
	public function roles()
	{
		return $this->db->get_where('roles', ['name !=' => 'DEVELOPER'])->result_object();
	}

	public function roleUser($id)
	{
		$this->db->select('a.id, b.name')->from('role_user as a');
		$this->db->join('roles as b', 'b.id = a.role_id');
		$this->db->where('a.user_id', $id);
		return $this->db->get()->result_object();
	}

	public function roleUserForSwitch($user, $role)
	{
		$this->db->select('b.id, b.name')->from('role_user as a');
		$this->db->join('roles as b', 'b.id = a.role_id');
		$this->db->where(['a.user_id' => $user, 'role_id !=' => $role]);
		return $this->db->get()->result_object();
	}
    public function getdata()
    {
        $status = $this->input->post('status', true);
        $role = $this->input->post('role', true);

        $this->db->select('*')->from('users')->where('role !=', 'DEV');
        if ($status != '') {
            $this->db->where('status', $status);
        }

        if ($role != '') {
            $this->db->where('role', $role);
        }
        $data = $this->db->get()->result_object();

        $this->db->select('*')->from('users')->where('role !=', 'DEV');
        if ($status != '') {
            $this->db->where('status', $status);
        }

        if ($role != '') {
            $this->db->where('role', $role);
        }
        $amount = $this->db->get()->num_rows();

        return [$data, $amount];
    }

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $status = $this->input->post('status', true);

        $this->db->where('id', $id)->update('users', [
            'status' => $status
        ]);

        if ($this->db->affected_rows() > 0) {
            $result = [
                'status' => 200
            ];
        } else {
            $result = [
                'status' => 400
            ];
        }

        return $result;
    }

    public function save()
    {
        $username = mt_rand(10000, 99999);
        $data = [
            'name' => strtoupper($this->input->post('name', true)),
            'username' => $username,
            'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
            'status' => 'INACTIVE'
        ];
        $this->db->insert('users', $data);

        if ($this->db->affected_rows() > 0) {
            return $username;
        } else {
            return 0;
        }
    }

	public function saveSetRole()
	{
		$user = $this->input->post('user', true);
		$role = $this->input->post('role', true);

		if ($user == '' || $role == '') {
			return [
				'status' => 400,
				'message' => 'Pastikan semua sudah diisi'
			];
		}

		$cekUser = $this->db->get_where('users', ['id' => $user])->num_rows();
		if ($cekUser <= 0) {
			return [
				'status' => 400,
				'message' => 'Menu tidak valid'
			];
		}

		$cekRole = $this->db->get_where('roles', [
			'id' => $role
		])->num_rows();
		if ($cekRole <= 0) {
			return [
				'status' => 400,
				'message' => 'Role tidak valid'
			];
		}

		$cekRoleUser = $this->db->get_where('role_user', [
			'role_id' => $role, 'user_id' => $user
		])->num_rows();
		if ($cekRoleUser > 0) {
			return [
				'status' => 400,
				'message' => 'Sudah diatur sebelumnya'
			];
		}

		$this->db->insert('role_user', [
			'user_id' => $user,
			'role_id' => $role
		]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 500,
				'message' => 'Gagal saat menyimpan data'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function deleteRoleUser()
	{
		$id = $this->input->post('id', true);

		$this->db->where('id', $id)->delete('role_user');
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 500,
				'message' => 'Gagal saat menyimpan data'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}
}
