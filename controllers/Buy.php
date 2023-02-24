<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Buy extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('BuyModel', 'bm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Pangkas Rambut',
            'step' => $this->bm->step(),
            'setting' => $this->bm->setting()
        ];
        $this->load->view('buy/buy', $data);
    }

    public function loadRecap()
    {
        $data = $this->bm->loadRecap();
        $data = [
            'data' => $data
        ];
        $this->load->view('buy/ajax-recap', $data);
    }

    public function loadData()
    {
        $get = $this->bm->loadRecap();
        $data = [
            'data' => $get[2]
        ];
        $this->load->view('buy/ajax-load', $data);
    }

    public function checkNis()
    {
        $result = $this->bm->checkNis();

        echo json_encode($result);
    }

    public function save()
    {
        $result = $this->bm->save();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->bm->getData()
        ];
        $this->load->view('buy/ajax-data', $data);
    }

    public function checkNominal()
    {
        $result = $this->bm->checkNominal();

        echo json_encode($result);
    }
}