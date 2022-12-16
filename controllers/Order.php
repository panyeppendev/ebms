<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('OrderModel', 'om');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi Pembelian',
            'step' => $this->om->step(),
            'setting' => $this->om->setting()
        ];
        $this->load->view('order/order', $data);
    }

    public function checkNIS()
    {
        $result = $this->om->checkNIS();
        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->om->getData()
        ];
        $this->load->view('order/ajax-check', $data);
    }

    public function getcheck()
    {
        $data = [
            'data' => $this->om->getData()
        ];
        $this->load->view('order/ajax-check-deposit', $data);
    }

    public function save()
    {
        $result = $this->om->save();

        echo json_encode($result);
    }

    public function loadData()
    {
        $get = $this->om->loadRecap();
        $data = [
            'data' => $get
        ];
        $this->load->view('order/ajax-data', $data);
    }

    public function loadRecap()
    {
        $get = $this->om->loadRecap();
        $data = [
            'data' => $get
        ];
        $this->load->view('order/ajax-recap', $data);
    }
}
