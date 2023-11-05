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
			'title' => 'Pencairan Non Tunai',
			'setting' => $this->tm->setting(),
		];
		$this->load->view('transaction/transaction', $data);
	}

	public function dailyTotal()
	{
		$result = $this->tm->dailyTotal();

		echo json_encode($result);
	}

	public function checkCard()
	{
		$result = $this->tm->checkCard();

		echo json_encode($result);
	}

	public function transactions()
	{
		$data = $this->tm->transactions();
		$data = [
			'transactions' => $data
		];
		$this->load->view('transaction/ajax-data', $data);
	}

	public function save()
	{
		$result = $this->tm->save();

		echo json_encode($result);
	}

	public function destroy()
	{
		$result = $this->tm->destroy();

		echo json_encode($result);
	}

	public function getData()
	{
		$result = $this->tm->getData();
		$data = [
			'data' => $result
		];
		$this->load->view('transaction/ajax-check', $data);
	}
}
