<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MenuModel extends CI_Model
{
    public function getMenu($role)
    {
        if ($role != '1') {
			$this->db->select('*')->from('role_menu')->join('menus', 'menus.id = menu_id');
			$this->db->where(['role_id' => $role, 'status' => 'ACTIVE']);
			return $this->db->order_by('order', 'ASC')->group_by('menu_id')->get()->result_object();
        }

		return $this->db->get_where('menus', ['status' => 'ACTIVE'])->result_object();
    }

    public function getdata()
    {
        $status = $this->input->post('status', true);

        $this->db->select('*')->from('menus');
        if ($status != '') {
            $this->db->where('status', $status);
        }
        $data = $this->db->get()->result_object();

        return $data;
    }

    public function getURL($url)
    {
        $data = $this->db->get_where('menus', ['url' => $url])->row_object();
        return $data->id;
    }

    public function cekUserMenu($id, $role)
    {
        return $this->db->get_where('role_menu', [
            'menu_id' => $id, 'role_id' => $role
        ])->num_rows();
    }

    public function save()
    {
        $data = [
            'name' => ucwords($this->input->post('name', true)),
            'icon' => $this->input->post('icon', true),
            'url' => $this->input->post('url', true),
            'status' => 'ACTIVE'
        ];
        $this->db->insert('menus', $data);

        return $this->db->affected_rows();
    }

    public function updatestatus()
    {
        $id = $this->input->post('id', true);
        $status = $this->input->post('status', true);
        $this->db->where('id', $id)->update('menus', ['status' => $status]);

        return $this->db->affected_rows();
    }

    public function saveSet()
    {
        $menu = $this->input->post('menu', true);
        $order = $this->input->post('order', true);
        $role = $this->input->post('role', true);

		if ($menu == '' || $order == '' || $role == '') {
			return [
				'status' => 400,
				'message' => 'Pastikan semua sudah diisi'
			];
		}

        $cekMenu = $this->db->get_where('menus', ['id' => $menu])->num_rows();
		if ($cekMenu <= 0) {
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

		$cekRoleMenu = $this->db->get_where('role_menu', [
			'role_id' => $role, 'menu_id' => $menu
		])->num_rows();
		if ($cekRoleMenu > 0) {
			return [
				'status' => 400,
				'message' => 'Sudah diatur sebelumnya'
			];
		}

		$this->db->insert('role_menu', [
			'menu_id' => $menu,
			'role_id' => $role,
			'order' => $order
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

    public function getrole($id)
    {
        return $this->db->get_where('user_menu', [
            'menu_id' => $id, 'role !=' => 'DEV'
        ])->result_object();
    }

	public function roleMenu($id)
	{
		$this->db->select('a.id, b.name')->from('role_menu as a');
		$this->db->join('roles as b', 'b.id = a.role_id');
		$this->db->where('a.menu_id', $id);
		return $this->db->get()->result_object();
	}

    public function deleteRoleMenu()
    {
        $id = $this->input->post('id', true);
        $this->db->where('id', $id)->delete('role_menu');
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
