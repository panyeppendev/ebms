<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Presence extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('DataModel', 'dm');
		$this->load->model('PresenceModel', 'pm');
		CekLoginAkses();
	}

	public function index()
	{
		$level = $this->input->post('level', true);
		$grade = $this->input->post('grade', true);
		$data = [
			'title' => 'Absensi',
			'level' => $level,
			'grade' => $grade,
			'grades' => grade($level),
			'rombel' => $this->pm->getRombel($level, $grade),
		];
		$this->load->view('presence/presence', $data);
	}

	public function loadData()
	{
		$result = $this->pm->loadData();

		$this->load->view('presence/ajax-index', ['data' => $result]);
	}

	public function loadAdd()
	{
		$result = $this->pm->loadAdd();

		$this->load->view('presence/ajax-add', ['data' => $result]);
	}

	public function save()
	{
		$result = $this->pm->save();
		echo json_encode($result);
	}
}
