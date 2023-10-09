<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingPackageModel extends CI_Model
{
	public function settings()
	{
		$package = $this->input->post('package', true);
		$this->db->select('a.nominal, b.id, b.name')->from('package_limit as a')->join('accounts as b', 'b.id = a.account_id');
		return $this->db->where('a.package_id', $package)->get()->result_object();
	}
    public function packages()
    {
        return $this->db->order_by('name', 'ASC')->get('packages')->result_object();
    }

	public function limits($package)
	{
		if ($package) {
			$this->db->select('b.id, b.name')->from('package_detail as a')->join('accounts as b', 'b.id = a.account_id');
			return $this->db->where('a.package_id', $package)->get()->result_object();
		}

		return '';
	}

	public function others()
	{
		return $this->db->get_where('accounts', [
			'category' => 'OTHER', 'status' => 'ACTIVE'
		])->result_object();
	}

	public function store()
	{
		$package = $this->input->post('package', true);
		$id = $this->input->post('id', true);
		$nominal = $this->input->post('nominal', true);
		$qty = $this->input->post('qty', true);

		$index = 0;
		if ($id) {
			foreach ($id as $d) {
				$value = str_replace('.', '', $nominal[$index]);
				if ($value != 0) {
					$check = $this->db->get_where('package_limit', [
						'package_id' => $package, 'account_id' => $d
					])->row_object();

					if ($check) {
						$this->db->where('id', $check->id)->update('package_limit', [
							'nominal' => $value, 'qty' => $qty[$index]
						]);
					} else {
						$this->db->insert('package_limit', [
							'package_id' => $package,
							'account_id' => $d,
							'nominal' => $value,
							'qty' => $qty[$index]
						]);
					}
				}
				$index++;
			}
		}
		return [
			'status' => true,
			'message' => $package
		];
	}

	public function getNominal($package, $account)
	{
		$data = $this->db->get_where('package_limit', ['package_id' => $package, 'account_id' => $account])->row_object();
		if ($data) {
			return [$data->nominal, $data->qty];
		}

		return ['0', '0'];
	}
}
