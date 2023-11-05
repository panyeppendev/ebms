<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Deposit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('DepositModel', 'dpm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Tabungan'
        ];
        $this->load->view('deposit/deposit', $data);
    }

    public function loadData()
    {
        $data = [
            'datas' => $this->dpm->loadData()
        ];
        $this->load->view('deposit/ajax-data', $data);
    }

	public function create()
	{
		$data = [
			'title' => 'Tambah Tabungan'
		];
		$this->load->view('deposit/create/deposit-create', $data);
	}

    public function detail()
    {
        $data = [
            'data' => $this->dpm->detail()
        ];
        $this->load->view('deposit/ajax-detail', $data);
    }

    public function checkId()
    {
        $result = $this->dpm->checkId();

        echo json_encode($result);
    }

    public function showCheck()
    {
        $data = [
            'data' => $this->dpm->showCheck()
        ];
        $this->load->view('deposit/create/ajax-check', $data);
    }

    public function store()
    {
        $result = $this->dpm->store();

        echo json_encode($result);
    }
}
