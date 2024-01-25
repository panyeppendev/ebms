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
            'title' => 'Pencairan Tunai',
            'setting' => $this->dbm->setting(),
        ];
        $this->load->view('disbursement/disbursement', $data);
    }

	public function dailyTotal()
	{
		$result = $this->dbm->dailyTotal();

		echo json_encode($result);
	}

	public function checkCard()
	{
		$result = $this->dbm->checkCard();

		echo json_encode($result);
	}

    public function disbursements()
    {
        $data = $this->dbm->disbursements();
        $data = [
            'disbursements' => $data
        ];
        $this->load->view('disbursement/ajax-data', $data);
    }

    public function save()
    {
        $result = $this->dbm->save();

        echo json_encode($result);
    }

	public function destroy()
	{
		$result = $this->dbm->destroy();

		echo json_encode($result);
	}

	public function getData()
	{
		$result = $this->dbm->getData();
		$data = [
			'data' => $result
		];
		$this->load->view('disbursement/ajax-check', $data);
	}

	public function searchCard($name)
	{
		$result = $this->dbm->searchCard($name);
		$data = [
			'data' => $result
		];
		$this->load->view('transaction/ajax-search', $data);
	}
}
