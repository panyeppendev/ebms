<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('ReportModel', 'rm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Rekapitulasi',
            'step' => $this->rm->step()
        ];
        $this->load->view('report/report', $data);
    }
}
