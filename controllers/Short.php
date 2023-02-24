<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Short extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('ShortModel', 'sm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Surat Jarak Dekat',
            'step' => $this->sm->step(),
            'setting' => $this->sm->setting(),
			'constitutions' => $this->sm->constitution()
        ];
        $this->load->view('short/short', $data);
    }

    public function loadRecap()
    {
        $data = $this->sm->loadRecap();
        $data = [
            'data' => $data
        ];
        $this->load->view('short/ajax-recap', $data);
    }

    public function loadData()
    {
        $get = $this->sm->loadRecap();
        $data = [
            'data' => $get[2]
        ];
        $this->load->view('short/ajax-load', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Izin',
            'step' => $this->sm->step(),
            'setting' => $this->sm->setting(),
            'reasons' => $this->sm->reason()
        ];
        $this->load->view('short/short-add', $data);
    }

    public function checkNis()
    {
        $result = $this->sm->checkNis();

        echo json_encode($result);
    }

    public function save()
    {
        $result = $this->sm->save();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->sm->getData(),
			'message' => $this->input->post('message', true)
        ];
        $this->load->view('short/ajax-data', $data);
    }

    public function loadPermission()
    {
        $data = [
            'permissions' => $this->sm->loadPermission()[1]
        ];
        $this->load->view('short/ajax-permission', $data);
    }

    public function loadCountPermission()
    {
        $data = [
            'permission' => $this->sm->loadPermission()[0]
        ];
        $this->load->view('short/ajax-count-permission', $data);
    }

    public function checkNominal()
    {
        $result = $this->sm->checkNominal();

        echo json_encode($result);
    }

	public function doBack()
	{
		$result = $this->sm->doBack();

		echo json_encode($result);
	}

	public function getIdPermission()
	{
		$result = $this->sm->getIdPermission();

		echo json_encode($result);
	}

	public function loadConstitution()
	{
		$data = [
			'data' => $this->sm->loadConstitution()
		];
		$this->load->view('short/ajax-punishment', $data);
	}

	public function savePunishment()
	{
		$result = $this->sm->savePunishment();

		echo json_encode($result);
	}

}
