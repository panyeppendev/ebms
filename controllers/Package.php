<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Package extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('PackageModel', 'pm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Pembayaran Paket',
            'step' => $this->pm->step()
        ];
        $this->load->view('package/package', $data);
    }

    public function loadData()
    {
        $data = [
            'datas' => $this->pm->loadData()
        ];
        $this->load->view('package/ajax-data', $data);
    }

    public function checkId()
    {
        $result = $this->pm->checkId();

        echo json_encode($result);
    }

    public function showCheck()
    {
        $data = [
            'data' => $this->pm->showCheck()
        ];
        $this->load->view('package/ajax-check', $data);
    }

    public function showRate()
    {
        $data = [
            'data' => $this->pm->showRate()
        ];
        $this->load->view('package/ajax-rate', $data);
    }

    public function savePackage()
    {
        $result = $this->pm->savePackage();

        echo json_encode($result);
    }

    public function deletePackage()
    {
        $result = $this->pm->deletePackage();

        echo json_encode($result);
    }

    public function activePackage()
    {
        $result = $this->pm->activePackage();

        echo json_encode($result);
    }

    public function packageActivation()
    {
        $result = $this->pm->packageActivation();

        echo json_encode($result);
    }

    public function printOne()
    {
        $package = $this->input->post('invoice', true);

        redirect('package/print/' . encrypt_url($package));
    }

    public function printData()
    {
        $package = $this->input->post('id_package', true);

        redirect('package/print/' . encrypt_url($package));
    }

    public function print($package)
    {
        $package = decrypt_url($package);
        $data = [
            'title' => 'Print',
            'data' => $this->pm->dataPrint($package)
        ];
        $this->load->view('print/package-print', $data);
    }
}
