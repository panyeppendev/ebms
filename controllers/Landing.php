<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
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

	public function coba()
	{
		$period = $this->dm->getperiod();

		$getPackage = $this->db->get_where('packages', [
			'student_id' => '4300012',
			'step' => 9,
			'package !=' => 'UNKNOWN',
			'period' => $period
		])->row_object();

		$idPackage = $getPackage->id;

		$this->db->select('status, SUM(amount) as total')->from('package_transaction');
		$result = $this->db->where(['package_id' => $idPackage, 'type' => 'POCKET'])->group_by('status')->get()->result_object();
		echo $this->db->last_query();
	}
}
