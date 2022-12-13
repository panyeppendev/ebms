<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkdata extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('CheckdataModel', 'cdm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Check Data Santri Liburan',
            'setting' => $this->cdm->getSetting(),
            'data' => $this->cdm->showSetting()
        ];
        $this->load->view('checkdata/checkdata', $data);
    }

    public function checkNIS()
    {
        $result = $this->cdm->checkNIS();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->cdm->getData()[0],
            'requirement' => $this->cdm->getData()[1],
            'total' => $this->cdm->getData()[2],
            'yes' => $this->cdm->getData()[3]
        ];

        $this->load->view('checkdata/ajax-check', $data);
    }
}
