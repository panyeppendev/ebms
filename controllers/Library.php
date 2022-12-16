<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Library extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('LibraryModel', 'lm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi Pembelian',
            'step' => $this->lm->step(),
            'setting' => $this->lm->setting()
        ];
        $this->load->view('library/library', $data);
    }

    public function checkNIS()
    {
        $result = $this->lm->checkNIS();
        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->lm->getData()
        ];
        $this->load->view('library/ajax-check', $data);
    }

    public function getcheck()
    {
        $data = [
            'data' => $this->lm->getData()
        ];
        $this->load->view('library/ajax-check-deposit', $data);
    }

    public function save()
    {
        $result = $this->lm->save();

        echo json_encode($result);
    }

    public function loadData()
    {
        $get = $this->lm->loadRecap();
        $data = [
            'data' => $get
        ];
        $this->load->view('library/ajax-data', $data);
    }

    public function loadRecap()
    {
        $get = $this->lm->loadRecap();
        $data = [
            'data' => $get
        ];
        $this->load->view('library/ajax-recap', $data);
    }
}
