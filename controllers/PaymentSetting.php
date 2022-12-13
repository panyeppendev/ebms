<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PaymentSetting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('PaymentSettingModel', 'psm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Pengaturan Awal Sistem',
            'datas' => $this->psm->getRates(),
            'accountNew' => $this->psm->accountById()[0],
            'accountOld' => $this->psm->accountById()[1],
            'packageGeneral' => $this->psm->packageByType()[0],
            'packageAB' => $this->psm->packageByType()[1],
            'packageCD' => $this->psm->packageByType()[2],
            'setting' => $this->psm->getSetting(),
            'shifts' => $this->psm->loadShift(),
            'step' => $this->psm->step()
        ];
        $this->load->view('payment-setting/payment-setting', $data);
    }

    public function loadData()
    {
        $data = [
            'datas' => $this->psm->loadData()
        ];
        $this->load->view('payment-setting/ajax-data', $data);
    }

    public function loadPackage()
    {
        $data = [
            'datas' => $this->psm->loadPackage()
        ];
        $this->load->view('payment-setting/ajax-package', $data);
    }

    public function loadShift()
    {
        $data = [
            'shifts' => $this->psm->loadShift()
        ];
        $this->load->view('payment-setting/ajax-shift', $data);
    }

    public function saveRate()
    {
        $result = $this->psm->saveRate();

        echo json_encode($result);
    }

    public function setRate()
    {
        $result = $this->psm->setRate();

        echo json_encode($result);
    }

    public function setPayment()
    {
        $result = $this->psm->setPayment();

        echo json_encode($result);
    }

    public function setpackage()
    {
        $result = $this->psm->setpackage();

        echo json_encode($result);
    }

    public function savePackages()
    {
        $result = $this->psm->savePackages();

        echo json_encode($result);
    }

    public function savePackage()
    {
        $result = $this->psm->savePackage();

        echo json_encode($result);
    }

    public function saveShift()
    {
        $result = $this->psm->saveShift();

        echo json_encode($result);
    }

    public function saveStep()
    {
        $result = $this->psm->saveStep();

        echo json_encode($result);
    }

}
