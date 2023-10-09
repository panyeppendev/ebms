<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Disbursement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('DisbursementModel', 'dbm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Pencairan Uang Saku',
            'setting' => $this->dbm->setting(),
        ];
        $this->load->view('disbursement/disbursement', $data);
    }

	public function checkCard()
	{
		$result = $this->dbm->checkCard();

		echo json_encode($result);
	}

    public function disbursements()
    {
        $data = $this->dbm->disbursements();
        $data = [
            'disbursements' => $data
        ];
        $this->load->view('disbursement/ajax-data', $data);
    }

    public function save()
    {
        $result = $this->dbm->save();

        echo json_encode($result);
    }

	public function destroy()
	{
		$result = $this->dbm->destroy();

		echo json_encode($result);
	}
}
