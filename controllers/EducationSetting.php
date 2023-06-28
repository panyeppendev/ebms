<?php

defined('BASEPATH') or exit('No direct script access allowed');

class EducationSetting extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('DataModel', 'dm');
		$this->load->model('EducationSettingModel', 'esm');
		CekLoginAkses();
	}

	public function index()
	{
		$data = [
			'title' => 'Data Wali Kelas'
		];
		$this->load->view('education-setting/education-setting', $data);
	}

	public function set()
	{
		$level = $this->input->post('level', true);

		$data = [
			'title' => 'Atur Wali Kelas',
			'level' => $level,
			'kelas' => grade($level)
		];
		$this->load->view('education-setting/education-set', $data);
	}

	public function saveSet()
	{
		$result = $this->esm->saveSet();

		echo json_encode($result);
	}

	public function loadData()
	{
		$data = ['data' => $this->esm->loadData()];
		$this->load->view('education-setting/ajax-wali', $data);
	}

	public function setmurid()
	{
		$level = $this->input->post('level', true);
		$kelasFilter = $this->input->post('kelas', true);

		$data = [
			'title' => 'Atur Murid Kelas',
			'level' => $level,
			'kelas' => grade($level),
			'rombel' => $this->esm->getRombel($level, $kelasFilter),
			'kelas_filter' => $kelasFilter
		];
		$this->load->view('education-setting/education-murid-set', $data);
	}

	public function getAdd()
	{
		$data = [
			'data' => $this->esm->getAdd(),
			'rombel' => $this->input->post('rombel', true)
		];
		$this->load->view('education-setting/ajax-add', $data);
	}

	public function saveSetMurid()
	{
		$result = $this->esm->saveSetMurid();

		echo json_encode($result);
	}

	public function loadRombel()
	{
		$data = [
			'data' => $this->esm->loadRombel()
		];
		$this->load->view('education-setting/ajax-room', $data);
	}
}
