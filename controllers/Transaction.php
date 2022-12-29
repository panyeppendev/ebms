<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('TransactionModel', 'tm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi DPU',
            'step' => $this->tm->step(),
            'setting' => $this->tm->setting(),
            'shift' => $this->tm->getShift()
        ];
        $this->load->view('transaction/transaction', $data);
    }

    public function loadData()
    {
        $data = [
            'data' => $this->tm->loadData()
        ];
        $this->load->view('transaction/ajax-data', $data);
    }

    public function checkNIS()
    {
        $result = $this->tm->checkNIS();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->tm->getData()
        ];
        $this->load->view('transaction/ajax-check', $data);
    }
}
