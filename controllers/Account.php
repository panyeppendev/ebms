<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('AccountModel', 'am');
		CekLoginAkses();
	}

	public function index()
	{
		$data = [
			'title' => 'Akun Keuangan'
		];
		$this->load->view('account/account', $data);
	}

	public function accounts()
	{
		$data = [
			'accounts' => $this->am->accounts()
		];
		$this->load->view('account/ajax-account', $data);
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('category', 'Kategori', 'required');
		$this->form_validation->set_rules('name', 'Nama', 'required');

		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 400,
				'errors' => [
					'category' => form_error('category'),
					'name' => form_error('name'),
				]
			];
		}else{
			$response = $this->am->save();
		}

		echo json_encode($response);
	}

	public function edit()
	{
		$id = $this->input->post('id', true);
		$result = $this->am->edit($id);

		echo json_encode($result);
	}

	public function setStatus()
	{
		$result = $this->am->setStatus();

		echo json_encode($result);
	}
}
