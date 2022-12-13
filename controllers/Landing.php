<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LandingModel', 'lm');
    }

    public function index()
    {
        $data = [
            'title' => 'Cek Data Paket'
        ];
        $this->load->view('landing/landing', $data);
    }

    public function checkSaldo()
    {
        $result = $this->lm->checkSaldo();

        echo json_encode($result);
    }

    public function getdata()
    {
        $data = ['data' => $this->lm->getdata()];

        $this->load->view('landing/ajax-data', $data);
    }
}
