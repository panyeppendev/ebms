<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SetDaily extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('PurchaseModel', 'pm');
        $this->load->model('SetDailyModel', 'sdm');
        CekLoginAkses();
    }

    public function index()
    {
		$setting = $this->sdm->setting();
        $data = [
            'title' => 'Buka Tutup Harian',
			'setting' => $setting,
			'open' => 'BUKA TRANSAKSI TANGGAL '.date('Y-m-d'),
			'close' => 'TUTUP TRANSAKSI TANGGAL '.@$setting->created_at,
			'accounts' => $this->sdm->accounts()
        ];
        $this->load->view('set-daily/set-daily', $data);
    }

	public function open()
	{
		$result = $this->sdm->open();

		echo json_encode($result);
	}

	public function close()
	{
		$result = $this->sdm->close();

		echo json_encode($result);
	}
}
