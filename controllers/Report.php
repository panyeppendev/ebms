<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('ReportModel', 'rm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan'
        ];
        $this->load->view('report/report', $data);
    }

	public function purchase()
	{
		$data = [
			'title' => 'Laporan Pembelian Paket'
		];
		$this->load->view('report/purchase/report-purchase', $data);
	}

	public function purchases()
	{
		$data = [
			'purchases' => $this->rm->purchases()[0],
			'detail' => $this->rm->purchases()[1],
			'start' => $this->input->post('start', true),
			'end' => $this->input->post('end', true)
		];
		$this->load->view('report/purchase/ajax-report-purchase', $data);
	}

	public function exportPurchase()
	{
		$this->load->library('pdfgenerator');
		$data = [
			'purchases' => $this->rm->purchases()[0],
			'detail' => $this->rm->purchases()[1],
			'start' => $this->input->post('start', true),
			'end' => $this->input->post('end', true)
		];
		$file_pdf = 'laporan-pembelian-paket';
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "portrait";

		$html = $this->load->view('report/purchase/print-report-purchase', $data, true);
		$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
	}

	public function mutation()
	{
		$data = [
			'title' => 'Laporan Mutasi Kas',
			'accounts' => $this->rm->accounts(),
			'grades' => $this->rm->administration()[0],
			'levels' => $this->rm->administration()[1],
			'rooms' => $this->rm->administration()[2],
		];
		$this->load->view('report/mutation/report-mutation', $data);
	}

    public function mutations()
    {
        $data = [
            'mutations' => $this->rm->mutations()[0],
            'date' => $this->rm->mutations()[1],
            'account' => $this->rm->mutations()[2],
        ];

        $this->load->view('report/mutation/ajax-report-mutation', $data);
    }

	public function exportMutation()
	{
		$this->load->library('pdfgenerator');
		$data = [
			'mutations' => $this->rm->mutations()[0],
			'date' => $this->rm->mutations()[1],
			'account' => $this->rm->mutations()[2],
		];
		$file_pdf = 'laporan-mutasi-kas-'.date('d-m-Y');
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "landscape";

		$html = $this->load->view('report/mutation/print-report-mutation', $data, true);
		$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
	}

	public function cashFlow()
	{
		$data = [
			'title' => 'Laporan Arus Kas',
			'accounts' => $this->rm->accounts()
		];
		$this->load->view('report/cashflow/report-cashflow', $data);
	}

	public function cashFlows()
	{
		$data = [
			'cashFlows' => $this->rm->cashFlows()[0],
			'date' => $this->rm->cashFlows()[1],
			'account' => $this->rm->cashFlows()[2],
		];

		$this->load->view('report/cashflow/ajax-report-cashflow', $data);
	}

	public function exportCashFlow()
	{
		$this->load->library('pdfgenerator');
		$data = [
			'cashFlows' => $this->rm->cashFlows()[0],
			'date' => $this->rm->cashFlows()[1],
			'account' => $this->rm->cashFlows()[2],
		];
		$file_pdf = 'laporan-arus-kas-'.date('d-m-Y');
		// setting paper
		$paper = 'A4';
		//orientate paper portrait / landscape
		$orientation = "portrait";

		$html = $this->load->view('report/cashflow/print-report-cashflow', $data, true);
		$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
	}

}
