<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('HomeModel', 'hm');
        CekLogin();
    }

    public function index()
    {
        $data = [
            'title' => 'e-bms Sistem',
//            'step' => $this->hm->step(),
//            'data' => $this->hm->getdata(),
            'class' => 'active'
        ];
        $this->load->view('home/home', $data);
    }

    public function coba()
    {
        $name = $this->input->post('name', true);
        $domicile = $this->input->post('domicile', true);
        $period = $this->dm->getperiod();

        $this->db->select('a.student_id, SUM(b.amount) AS amount')->from('packages AS a');
        $this->db->join('package_transaction AS b', 'b.package_id = a.id');
        $depositDebet = $this->db->where([
            'a.student_id' => 4300002, 'a.period' => $period, 'b.type' => 'DEPOSIT'
        ])->get()->row_object();
        if (!$depositDebet || $depositDebet->amount == '') {
            $depositDebet = 0;
        } else {
            $depositDebet = $depositDebet->amount;
        }

        echo $depositDebet;
    }
}
