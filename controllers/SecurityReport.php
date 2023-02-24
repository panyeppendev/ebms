<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SecurityReport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('SecurityReportModel', 'sm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Atur Pembelian'
        ];
        $this->load->view('securityreport/securityreport', $data);
    }

	public function printBarber()
	{
		$date = $this->input->get('date', true);
		$data = [
			'title' => 'Data Pendaftar Pangkas - '.dateIDFormatShort($date),
			'date' => dateIDFormatShort($date),
			'data' => $this->sm->dataBarber($date)
		];
		$this->load->view('print/print-barber', $data);
	}

	public function printPenalty()
	{
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);
		if ($start !== '0' && $end !== '0') {
			$date = 'Tanggal: '.dateIDFormatShort($start).' - '.dateIDFormatShort($end);
		}else{
			$date = 'Semua Tanggal';
		}
		$data = [
			'title' => 'Data Pelanggaran',
			'date' => $date,
			'data' => $this->sm->dataPenalty($start, $end)
		];
		$this->load->view('print/print-penalty', $data);
	}

}
