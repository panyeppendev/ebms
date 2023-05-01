<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentRegistration extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('DataModel', 'dm');
		$this->load->model('PaymentRegistrationModel', 'pm');
		CekLoginAkses();
	}

	public function index()
	{
		$data = [
			'setting' => $this->pm->setting(),
			'title' => 'Pembayaran Biaya Pendidikan'
		];
		$this->load->view('paymentregistration/paymentregistration', $data);
	}

	public function loadData()
	{
		$data = [
			'datas' => $this->pm->loadData()
		];
		$this->load->view('paymentregistration/ajax-data', $data);
	}

	public function checkId()
	{
		$result = $this->pm->checkId();

		echo json_encode($result);
	}

	public function showCheck()
	{
		$data = [
			'data' => $this->pm->showCheck()[0],
			'old' => $this->pm->showCheck()[1],
		];
		$this->load->view('paymentregistration/ajax-check', $data);
	}

	public function save()
	{
		$result = $this->pm->save();

		echo json_encode($result);
	}

	public function printOne()
	{
		$package = $this->input->post('invoice', true);

		redirect('paymentregistration/print/' . encrypt_url($package));
	}

	public function printData()
	{
		$package = $this->input->post('id_package', true);

		redirect('paymentregistration/print/' . encrypt_url($package));
	}

	public function print($package)
	{
		$package = decrypt_url($package);
		$data = [
			'title' => 'Print',
			'data' => $this->pm->dataPrint($package)
		];
		$this->load->view('print/payment-registration-print', $data);
	}
}
