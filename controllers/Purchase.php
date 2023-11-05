<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('PurchaseModel', 'pm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi Paket',
        ];
        $this->load->view('purchase/purchase', $data);
    }

	public function create()
	{
		$data = [
			'title' => 'Pembelian Paket',
			'packages' => $this->pm->packages(),
			'addons' => $this->pm->addons()
		];
		$this->load->view('purchase/create/purchase-create', $data);
	}

    public function save()
    {
        $data = [
			'data' => $this->pm->save()
		];
		$this->load->view('purchase/create/ajax-check-purchase', $data);
    }

	public function purchases()
	{
		$data = [
			'purchases' => $this->pm->purchases()
		];
		$this->load->view('purchase/ajax-data', $data);
    }

	public function store()
	{
		$result = $this->pm->store();

		echo json_encode($result);
	}

	public function invoice()
	{
		$data = [
			'title' => 'Print Invoice',
			'data' => $this->pm->invoice()[0],
			'package' => $this->pm->invoice()[1],
			'addon' => $this->pm->invoice()[2]
		];

		$this->load->view('print/purchase', $data);
	}

	public function activation()
	{
		$data = [
			'title' => 'Aktivasi Paket'
		];
		$this->load->view('purchase/activation/purchase-activation', $data);
	}

	public function purchasebyid()
	{
		$data = [
			'purchases' => $this->pm->purchasebyid()
		];
		$this->load->view('purchase/activation/ajax-check-activation', $data);
	}

	public function activate()
	{
		$result = $this->pm->activate();

		echo json_encode($result);
	}

	public function finished()
	{
		$result = $this->pm->finished();

		echo json_encode($result);
	}

	public function destroy()
	{
		$result = $this->pm->destroy();

		echo json_encode($result);
	}

	public function setPackageName()
	{
		$result = $this->db->get('purchases')->result_object();
		if ($result) {
			foreach ($result as $item) {
				$id = $item->id;
				$packageId = $item->package_id;
				$package = $this->db->get_where('packages', ['id' => $packageId])->row_object();
				if ($package) {
					$this->db->where('id', $id)->update('purchases', ['package_name' => $package->name]);
				}
			}
		}
	}

	public function setDisbursementDaily()
	{
		$date = $this->db->group_by('created_at')->get('disbursements')->result_object();
		if ($date) {
			$this->pm->setDisbursementDailyRecap($date->created_at);
		}
	}
}
