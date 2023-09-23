<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('RoleModel', 'rm');
		CekLoginAkses();
	}

	public function index()
	{
		$data = [
			'title' => 'Role'
		];
		$this->load->view('role/role', $data);
	}

	public function roles()
	{
		$data = [
			'roles' => $this->rm->roles()
		];
		$this->load->view('role/ajax-role', $data);
	}

	public function save()
	{
		$result = $this->rm->save();

		echo json_encode($result);
	}
}
