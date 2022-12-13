<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('PurchaseModel', 'pm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi Pembelian',
            'step' => $this->pm->step(),
            'setting' => $this->pm->setting()
        ];
        $this->load->view('purchase/purchase', $data);
    }

    public function checkNIS()
    {
        $result = $this->pm->checkNIS();
        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->pm->getData()
        ];
        $this->load->view('purchase/ajax-check', $data);
    }

    public function save()
    {
        $result = $this->pm->save();

        echo json_encode($result);
    }

    public function loadData()
    {
        $get = $this->pm->loadRecap();
        $data = [
            'data' => $get
        ];
        $this->load->view('purchase/ajax-data', $data);
    }

    public function loadRecap()
    {
        $get = $this->pm->loadRecap();
        $data = [
            'data' => $get
        ];
        $this->load->view('purchase/ajax-recap', $data);
    }
}
