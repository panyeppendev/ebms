<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Constitution extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('DataModel', 'dm');
		$this->load->model('ConstitutionModel', 'cm');
		CekLoginAkses();
	}

	public function index()
	{
		//SET DEFAULT CONSTITUTION
		$this->cm->setConstitutionDefault();

		$data = [
			'title' => 'Tata Tertib Pesantren'
		];
		$this->load->view('constitution/constitution', $data);
	}

	public function save()
	{
		$result = $this->cm->save();

		echo json_encode($result);
	}

	public function loadData()
	{
		$data = [
			'datas' => $this->cm->loadData()
		];
		$this->load->view('constitution/ajax-data', $data);
	}

	public function getById()
	{
		$result = $this->cm->getById();

		echo json_encode($result);
	}

	public function destroy()
	{
		$result = $this->cm->destroy();

		echo json_encode($result);
	}
}
