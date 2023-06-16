<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Punishment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('PunishmentModel', 'pm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Ta\'zir Pelanggaran'
        ];
        $this->load->view('punishment/punishment', $data);
    }

    public function loadRecap()
    {
        $data = $this->pm->loadRecap();
        $data = [
            'data' => $data
        ];
        $this->load->view('punishment/ajax-recap', $data);
    }

    public function loadDetail()
    {
        $data = [
            'data' => $this->pm->loadDetail()
        ];
        $this->load->view('punishment/ajax-load', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Ta\'zir',
            'constitutions' => $this->pm->constitution()
        ];
        $this->load->view('punishment/punishment-add', $data);
    }

    public function checkNis()
    {
        $result = $this->pm->checkNis();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->pm->getData()
        ];
        $this->load->view('punishment/ajax-data', $data);
    }

    public function loadPunishment()
    {
        $data = [
            'punishments' => $this->pm->loadPunishment()[1]
        ];
        $this->load->view('punishment/ajax-punishment', $data);
    }

    public function loadCountPunishment()
    {
        $data = [
            'punishment' => $this->pm->loadPunishment()[0]
        ];
        $this->load->view('punishment/ajax-count-punishment', $data);
    }

	public function search()
	{
		$data = [
			'datas' => $this->pm->search()
		];
		$this->load->view('punishment/ajax-search', $data);
	}
}
