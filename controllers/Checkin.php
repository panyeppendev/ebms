<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('CheckinModel', 'cm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Check In Santri Liburan',
            'setting' => $this->cm->getSetting(),
            'data' => $this->cm->showSetting(),
			'rooms' => $this->cm->rooms()
        ];
        $this->load->view('checkin/checkin', $data);
    }

    public function checkNIS()
    {
        $result = $this->cm->checkNIS();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->cm->getData()[0],
            'requirement' => $this->cm->getData()[1],
            'total' => $this->cm->getData()[2],
            'yes' => $this->cm->getData()[3]
        ];

        $this->load->view('checkin/ajax-check', $data);
    }

    public function loaddata()
    {
        $data = [
            'datas' => $this->cm->loaddata(),
        ];

        $this->load->view('checkin/ajax-data', $data);
    }

	public function printOut()
	{
		$data = [
			'title' => 'Print Out Checkin Liburan',
			'datas' => $this->cm->printOut(),
			'domicile' => $this->input->post('domicile', true)
		];
		$this->load->view('print/checkin', $data);
	}
}
