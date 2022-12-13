<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        CekLogin();
    }

    public function index()
    {
        $data = [
            'title' => 'e-bms Sistem',
            'class' => 'active'
        ];
        $this->load->view('home/home', $data);
    }
}
