<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Distribution extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('DistributionModel', 'tm');
        CekLoginAkses();
    }

	public function index()
	{
		$data = [
			'title' => 'Pengaturan',
			'setting' => $this->tm->setting(),
			'dailySetting' => $this->tm->dailySetting(),
		];
		$this->load->view('distribution/distribution', $data);
	}

	public function set()
	{
		$result = $this->tm->set();

		echo json_encode($result);
	}

	public function create()
	{
		$data = [
			'title' => 'Distribusi Makan',
			'setting' => $this->tm->setting(),
			'dailySetting' => $this->tm->dailySetting(),
		];
		$this->load->view('distribution/create/distribution-create', $data);
	}

	public function checkCard()
	{
		$result = $this->tm->checkCard();

		echo json_encode($result);
	}

	public function getData()
	{
		$result = $this->tm->getData();
		$data = [
			'data' => $result
		];
		$this->load->view('distribution/create/ajax-check', $data);
	}

	public function store()
	{
		$result = $this->tm->store();

		echo json_encode($result);
	}

	public function dailyTotal()
	{
		$result = $this->tm->dailyTotal();

		echo json_encode($result);
	}



	public function distributions()
	{
		$data = $this->tm->distributions();
		$data = [
			'distributions' => $data
		];
		$this->load->view('distribution/ajax-data', $data);
	}



	public function destroy()
	{
		$result = $this->tm->destroy();

		echo json_encode($result);
	}
}
