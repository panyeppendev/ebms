<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Disbursement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('DisbursementModel', 'dbm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Pencairan Uang Saku',
            'step' => $this->dbm->step(),
            'setting' => $this->dbm->setting(),
        ];
        $this->load->view('disbursement/disbursement', $data);
    }

    public function loadData()
    {
        $get = $this->dbm->loadRecap();
        $data = [
            'data' => $get
        ];
        $this->load->view('disbursement/ajax-data', $data);
    }

    public function loadRecap()
    {
        $data = $this->dbm->loadRecap();
        $data = [
            'data' => $data
        ];
        $this->load->view('disbursement/ajax-recap', $data);
    }

    public function setPocket()
    {
        $result = $this->dbm->setPocket();

        echo json_encode($result);
    }

    public function setFasting()
    {
        $result = $this->dbm->setFasting();

        echo json_encode($result);
    }

    public function checkNis()
    {
        $result = $this->dbm->checkNis();

        echo json_encode($result);
    }

    public function save()
    {
        $result = $this->dbm->save();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->dbm->getData()
        ];
        $this->load->view('disbursement/ajax-check', $data);
    }

    public function coba()
    {
        $now = date('Y-m-d');

        $this->db->select('SUM(amount) AS amount')->from('package_transaction');
        $this->db->where('DATE(created_at)', $now);
        $this->db->where_in('status', ['POCKET_CASH', 'DEPOSIT_CASH']);
        $amount = $this->db->get()->row_object();
        echo $this->db->last_query();
    }
}
