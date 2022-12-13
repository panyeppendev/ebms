<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Requirement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('RequirementModel', 'rm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Cek Persyaratan Liburan',
            'setting' => $this->rm->getSetting(),
            'data' => $this->rm->showSetting()
        ];
        $this->load->view('requirement/requirement', $data);
    }

    public function checkNIS()
    {
        $result = $this->rm->checkNIS();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->rm->getData()[0],
            'total' => $this->rm->getData()[1],
            'yes' => $this->rm->getData()[2]
        ];

        $this->load->view('requirement/ajax-check', $data);
    }

    public function checkin()
    {
        $data = [
            'data' => $this->rm->checkin(),
            'kelas' => $this->input->post('kelas', true)
        ];

        $this->load->view('requirement/ajax-checkin', $data);
    }

    public function saveCheckin()
    {
        $result = $this->rm->saveCheckin();

        echo json_encode($result);
    }
}
