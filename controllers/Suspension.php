<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suspension extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('SuspensionModel', 'sm');

        CekLoginAkses();
    }

    public function index()
    {
		//AUTO DONE SKORSING
		$auto = $this->sm->autoDoDone();

        $data = [
            'title' => 'Skorsing Santri',
			'auto' => $auto
        ];
        $this->load->view('suspension/suspension', $data);
    }

    public function loadSuspension()
    {
        $data = [
            'suspensions' => $this->sm->loadSuspension()[1]
        ];
        $this->load->view('suspension/ajax-suspension', $data);
    }

    public function loadCountSuspension()
    {
        $data = [
            'suspension' => $this->sm->loadSuspension()[0]
        ];
        $this->load->view('suspension/ajax-count-suspension', $data);
    }

	public function doActive()
	{
		$result = $this->sm->doActive();

		echo json_encode($result);
	}

	public function doDone()
	{
		$result = $this->sm->doDone();

		echo json_encode($result);
	}

	public function doCustom()
	{
		$result = $this->sm->doCustom();

		echo json_encode($result);
	}
}
