<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Security extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('SecurityModel', 'sm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Pengaturan Bagian Keamanan',
            'setting' => $this->sm->getSetting(),
            'data' => $this->sm->showSetting()
        ];
        $this->load->view('security/security', $data);
    }

    public function loadData()
    {
        $data = [
            'data' => $this->sm->loadData(),
            'setting' => $this->sm->getSetting()
        ];

        $this->load->view('security/ajax-data', $data);
    }

    public function save()
    {
        $result = $this->sm->save();

        echo json_encode($result);
    }

    public function delete()
    {
        $result = $this->sm->delete();

        echo json_encode($result);
    }

    public function changeStatus()
    {
        $result = $this->sm->changeStatus();

        echo json_encode($result);
    }

    public function getdata($id)
    {
        $result = $this->sm->getData($id);

        echo json_encode($result);
    }

    public function saveSetting()
    {
        $result = $this->sm->saveSetting();

        echo json_encode($result);
    }

    public function setSetting($type)
    {
        $result = $this->sm->setSetting($type);

        echo json_encode($result);
    }

    public function reset()
    {
        $this->db->truncate('holidays');
        $this->db->truncate('requirements');
        $this->db->truncate('requirement_detail');
        $this->db->truncate('security_setting');

        redirect('checkin');
    }
}
