<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Card extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('CardModel', 'cm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Kartu Santri',
        ];
        $this->load->view('card/card', $data);
    }

    public function checkid()
    {
        $result = $this->cm->checkid();

        echo json_encode($result);
    }

    public function getData()
    {
        $data = [
            'data' => $this->cm->getData()
        ];
        $this->load->view('card/ajax-check', $data);
    }

    public function save()
    {
        $result = $this->cm->save();

        echo json_encode($result);
    }
}
