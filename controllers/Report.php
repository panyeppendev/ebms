<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
            'title' => 'Rekapitulasi',
            'step' => $this->rm->step()
        ];
        $this->load->view('report/report', $data);
    }

    public function loadPaymentReport()
    {
        $step = $this->input->post('step', true);
        $data = [
            'step' => $step,
            'pocket' => $this->rm->reportPocket($step),
            'besidesPocket' => $this->rm->besidesPocket($step)
        ];

        $this->load->view('report/ajax-payment-report', $data);
    }

    public function loadDisbursementReport()
    {
        $step = $this->input->post('step', true);
        $data = [
            'step' => $step,
            'data' => $this->rm->disbursement($step)
        ];

        $this->load->view('report/ajax-disbursement-report', $data);
    }
}
