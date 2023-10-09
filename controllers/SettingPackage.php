<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingPackage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('SettingPackageModel', 'spm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Pengaturan Paket',
			'packages' => $this->spm->packages()
        ];
        $this->load->view('setting-package/setting-package', $data);
    }

	public function settings()
	{
		$data = [
			'title' => 'Pengaturan Paket',
			'settings' => $this->spm->settings()
		];
		$this->load->view('setting-package/ajax-setting-package', $data);
	}

	public function create()
	{
		$package = $this->input->post('package', true);
		$data = [
			'title' => 'Pengaturan Limit',
			'packages' => $this->spm->packages(),
			'packageSelected' => $package,
			'limits' => $this->spm->limits($package),
			'others' => $this->spm->others()
		];
		$this->load->view('setting-package/create/setting-package-create', $data);
	}

	public function store()
	{
		$result = $this->spm->store();

		echo json_encode($result);
	}
}
