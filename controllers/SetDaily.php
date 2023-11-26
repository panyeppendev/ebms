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
            'title' => 'Transaksi Harian',
			'setting' => $setting,
			'open' => 'BUKA TRANSAKSI TANGGAL '.date('Y-m-d'),
			'close' => 'TUTUP TRANSAKSI TANGGAL '.@$setting->created_at
        ];
        $this->load->view('set-daily/set-daily', $data);
    }

	public function create()
	{
		$data = [
			'title' => 'Rekening Pencairan',
			'accounts' => $this->sdm->accounts()
		];
		$this->load->view('set-daily/create/set-daily-create', $data);
	}

	public function store()
	{
		$result = $this->sdm->store();

		echo json_encode($result);
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

	public function reset()
	{
		$this->sdm->reset();

		redirect('setdaily');
	}

	public function coba()
	{
		$total = $this->db->get_where('daily_pocket_limit', ['student_id' => '14370197003'])->row_object();
		$result = $this->sdm->getDisbursement('14370197003', '2023-11-26');

		$all = ($total->pocket + $total->reserved) - $result;
		$date = $this->sdm->setting();

		if ($all > 0) {
			echo 1;
		}else{
			echo 0;
		}
	}
}
