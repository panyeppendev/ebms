<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Package extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('PackageModel', 'pm');
		CekLoginAkses();
	}

	public function index()
	{
		$data = [
			'title' => 'Daftar Paket'
		];
		$this->load->view('package/package', $data);
	}

	public function packages()
	{
		$data = [
			'packages' => $this->pm->packages()
		];
		$this->load->view('package/ajax-package', $data);
	}

	public function packageById()
	{
		$data = [
			'packages' => $this->pm->packageById()
		];
		$this->load->view('package/ajax-detail', $data);
	}

	public function create()
	{
		$data = [
			'title' => 'Tambah Paket',
			'accounts' => $this->pm->accounts()
		];
		$this->load->view('package/create/package-create', $data);
	}

	public function save()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_message('required', 'Nama %s harus diisi');
		$this->form_validation->set_message('alpha', 'Nama %s harus berupa alfabet');
		$this->form_validation->set_message('is_unique', 'Nama %s sudah ada sebelumnya');
		$this->form_validation->set_rules('name', 'Nama', 'required|alpha|is_unique[packages.name]');

		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => false,
				'errors' => [
					'name' => form_error('name'),
				]
			];
		}else{
			$response = [
				'status' => true,
				'errors' => []
			];
		}

		echo json_encode($response);
	}

	public function store()
	{
		$response = $this->pm->store();

		echo json_encode($response);
	}

	public function edit($id = '')
	{
		$data = [
			'title' => 'Edit Paket',
			'package' => $this->pm->package($id)
		];
		$this->load->view('package/create/package-edit', $data);
	}

	public function update()
	{
		$response = $this->pm->update();

		echo json_encode($response);
	}

	public function destroy()
	{
		$response = $this->pm->destroy();

		echo json_encode($response);
	}
}
