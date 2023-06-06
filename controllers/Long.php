<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Long extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('DataModel', 'dm');
		$this->load->model('LongModel', 'lm');
		CekLoginAkses();
	}

	public function index()
	{
		$data = [
			'title' => 'Surat Jarak Dekat',
			'step' => $this->lm->step(),
			'setting' => $this->lm->setting(),
			'constitutions' => $this->lm->constitution()
		];
		$this->load->view('long/long', $data);
	}

	public function loadRecap()
	{
		$data = $this->lm->loadRecap();
		$data = [
			'data' => $data
		];
		$this->load->view('long/ajax-recap', $data);
	}

	public function loadData()
	{
		$get = $this->lm->loadRecap();
		$data = [
			'data' => $get[2]
		];
		$this->load->view('long/ajax-load', $data);
	}

	public function add()
	{
		$data = [
			'title' => 'Tambah Izin',
			'step' => $this->lm->step(),
			'setting' => $this->lm->setting(),
			'reasons' => $this->lm->reason()
		];
		$this->load->view('long/long-add', $data);
	}

	public function checkNis()
	{
		$result = $this->lm->checkNis();

		echo json_encode($result);
	}

	public function save()
	{
		$result = $this->lm->save();

		echo json_encode($result);
	}

	public function getData()
	{
		$data = [
			'data' => $this->lm->getData(),
			'status' => $this->input->post('status', true),
			'message' => $this->input->post('message', true)
		];
		$this->load->view('long/ajax-data', $data);
	}

	public function loadPermission()
	{
		$data = [
			'permissions' => $this->lm->loadPermission()[1]
		];
		$this->load->view('long/ajax-permission', $data);
	}

	public function loadCountPermission()
	{
		$data = [
			'permission' => $this->lm->loadPermission()[0]
		];
		$this->load->view('long/ajax-count-permission', $data);
	}

	public function checkNominal()
	{
		$result = $this->lm->checkNominal();

		echo json_encode($result);
	}

	public function doBack()
	{
		$result = $this->lm->doBack();

		echo json_encode($result);
	}

	public function loadConstitution()
	{
		$data = [
			'data' => $this->lm->loadConstitution()
		];
		$this->load->view('long/ajax-punishment', $data);
	}

	public function savePunishment()
	{
		$result = $this->lm->savePunishment();

		echo json_encode($result);
	}

	public function license($id)
	{
		$id = decrypt_url($id);
		$data = [
			'title' => 'Print Out Surat Izin',
			'data' => $this->lm->loadPermissionById($id)
		];
		$this->load->view('print/print-permission', $data);
	}

	public function preLicense($id)
	{
		redirect('long/license/'.encrypt_url($id));
	}

	public function closePrint()
	{
		$result = $this->lm->closePrint();

		echo json_encode($result);
	}

	public function saveActive()
	{
		$result = $this->lm->saveActive();

		echo json_encode($result);
	}

	public function petition($id)
	{
		$id = decrypt_url($id);
		$data = [
			'title' => 'Print Out Permohonan Izin',
			'data' => $this->lm->loadPermissionById($id)
		];
		$this->load->view('print/print-petition', $data);
	}

	public function getIdPermission()
	{
		$result = $this->lm->getIdPermission();

		echo json_encode($result);
	}

}
