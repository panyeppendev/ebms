<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Store extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('StoreModel', 'sm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Atur Pembelian'
        ];
        $this->load->view('store/store', $data);
    }

    public function save()
    {
        $result = $this->sm->save();

        echo json_encode($result);
    }

    public function loadData()
    {
        $data = [
            'stores' => $this->sm->loadData()
        ];

        $this->load->view('store/ajax-data', $data);
    }

    public function saveReason()
    {
        $result = $this->sm->saveReason();

        echo json_encode($result);
    }

    public function deleteReason()
    {
        $result = $this->sm->deleteReason();

        echo json_encode($result);
    }

    public function loadReason()
    {
        $data = [
            'reasons' => $this->sm->loadReason()
        ];

        $this->load->view('store/ajax-reason', $data);
    }

	public function saveTerm()
	{
		$result = $this->sm->saveTerm();

		echo json_encode($result);
	}

	public function loadTerm()
	{
		$data = [
			'term' => $this->sm->loadTerm()
		];

		$this->load->view('store/ajax-term', $data);
	}
}
