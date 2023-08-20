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
            'title' => 'Laporan Keamanan'
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

	public function laporanPerizinan()
	{
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);
		if ($start !== '0' && $end !== '0') {
			$date = 'Tanggal: '.dateIDFormatShort($start).' - '.dateIDFormatShort($end);
		}else{
			$date = '';
		}
		$data = [
			'title' => 'Laporan Perizinan',
			'date' => $date,
			'data' => $this->sm->laporanPerizinan($start, $end),
			'short' => $this->sm->laporanAlasan($start, $end, 'SHORT'),
			'long' => $this->sm->laporanAlasan($start, $end, 'LONG'),
			'ten' => $this->sm->tenTop($start, $end),
			'payment' => $this->sm->laporanPayment($start, $end),
			'late' => $this->sm->laporanLate($start, $end),
			'short_late' => $this->sm->belumKembali($start, $end, 'SHORT'),
			'long_late' => $this->sm->belumKembali($start, $end, 'LONG')
		];

		$this->load->view('print/print-laporan-perizinan', $data);
	}

	public function laporanPelanggaran()
	{
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);
		if ($start !== '0' && $end !== '0') {
			$date = 'Tanggal: '.dateIDFormatShort($start).' - '.dateIDFormatShort($end);
		}else{
			$date = '';
		}
		$data = [
			'title' => 'Laporan Pelanggaran',
			'date' => $date,
			'data' => $this->sm->laporanPelanggaran($start, $end),
			'constitution' => $this->sm->orderPelanggaran($start, $end),
			'santri' => $this->sm->pelanggaranSantri($start, $end),
			'skorsing' => $this->sm->skorsing($start, $end),
			'skorsing_pelanggaran' => $this->sm->skorsingPelanggaran($start, $end)
		];

		$this->load->view('print/print-laporan-pelanggaran', $data);
	}

}
