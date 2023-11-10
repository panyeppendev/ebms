<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportModel extends CI_Model
{
    public function purchases()
    {
        $start = $this->input->post('start', true);
        $end = $this->input->post('end', true);
		if ($start == '' || $end == '') {
			return [
				[],
				[]
			];
		}

		//PURCHASE
		$this->db->select('COUNT(id) as qty, SUM(amount) as total, package_name as package')->from('purchases');
		$this->db->where(['DATE(created_at) >=' => $start, 'DATE(created_at) <=' => $end]);
		$data = $this->db->group_by('package_name')->order_by('package_name')->get()->result_object();

		$this->db->select('COUNT(a.id) as qty, SUM(a.nominal) as total, b.package_name as package, c.name')->from('purchase_detail as a');
		$this->db->join('purchases as b', 'b.id = a.purchase_id')->join('accounts as c', 'c.id = a.account_id');
		$this->db->where(['DATE(b.created_at) >=' => $start, 'DATE(b.created_at) <=' => $end]);
		$detail = $this->db->group_by('a.account_id')->order_by('a.account_id')->get()->result_object();

		return [$data, $detail];
    }

    public function accounts()
    {
		$this->db->select('a.account_id as id, b.name')->from('account_pocket as a');
		$pocket = $this->db->join('accounts as b', 'b.id = a.account_id')->get()->row_object();
		$data = [];
		if ($pocket) {
			$data[] = [
				'id' => $pocket->id,
				'name' => $pocket->name
			];
		}

		$this->db->select('a.account_id as id, b.name')->from('account_dpu as a');
		$dpu = $this->db->join('accounts as b', 'b.id = a.account_id')->get()->row_object();
		if ($dpu) {
			$data[] = [
				'id' => $dpu->id,
				'name' => $dpu->name
			];
		}

		$this->db->select('a.account_id as id, b.name')->from('account_breakfast as a');
		$breakfast = $this->db->join('accounts as b', 'b.id = a.account_id')->get()->row_object();
		if ($breakfast) {
			$data[] = [
				'id' => $breakfast->id,
				'name' => $breakfast->name
			];
		}

		return $data;
    }

	public function administration()
	{
		$grades = $this->db->select('class')->from('students')->group_by('class')->order_by('class')->get()->result_object();
		$schools = $this->db->select('level')->from('students')->group_by('level')->get()->result_object();
		$rooms = $this->db->get('rooms')->result_object();

		return [
			$grades,
			$schools,
			$rooms
		];
	}

	public function mutations()
	{
		$grade = $this->input->post('grade', true);
		$level = $this->input->post('level', true);
		$room = $this->input->post('room', true);
		$account = $this->input->post('account', true);
		$start = $this->input->post('start', true);
		$end = $this->input->post('end', true);

		$this->db->select('a.student_id as student, SUM(a.nominal) as total, b.name, b.class, b.level, b.domicile')->from('expenditures as a');
		$this->db->join('students as b', 'b.id = a.student_id');
		if ($grade) {
			$this->db->where('b.class', $grade);
		}

		if ($level) {
			$this->db->where('b.level', $level);
		}

		if ($room) {
			$this->db->where('domicile', $room);
		}

		$result = $this->db->where('a.account_id', $account)->group_by('a.student_id')->get()->result_object();

		$data = [];
		if ($result) {
			foreach ($result as $item) {
				$data[] = [
					'id' => $item->student,
					'name' => $item->name,
					'class' => $item->class.' - '.$item->level,
					'domicile' => $item->domicile,
					'credit' => $item->total,
					'debit' => $this->getDebit($item->student, $account, $start, $end)
				];
			}
		}

		return [
			$data,
			($start != '') ? 'Dari tanggal '.dateIDFormatShort($start).' s.d. '.dateIDFormatShort($end) : 'Seluruh waktu',
			$this->getAccountById($account)
		];
	}

	public function getDebit($id, $account, $start, $end)
	{
		$this->db->select('SUM(nominal) as total')->from('distribution_daily');
		$this->db->where(['account_id' => $account, 'student_id' => $id]);
		if ($start != '' && $end != '') {
			$this->db->where(['DATE(created_at) >=' => $start, 'DATE(created_at) <=' => $end]);
		}
		$result = $this->db->get()->row_object();

		if ($result) {
			return $result->total;
		}

		return 0;
	}

	public function getAccountById($id)
	{
		$data = $this->db->get_where('accounts', ['id' => $id])->row_object();
		if ($data) {
			return $data->name;
		}

		return '';
	}

	public function cashFlows()
	{
		$account = $this->input->post('account', true);
		$start = $this->input->post('start', true);
		$end = $this->input->post('end', true);

		$this->db->select('SUM(a.nominal) as total, c.name as role')->from('distribution_daily as a');
		$this->db->join('roles as c', 'c.id = a.role_id');
		$this->db->where('a.account_id', $account);
		if ($start != '' && $end != '') {
			$this->db->where(['a.created_at >=' => $start, 'a.created_at <=' => $end]);
		}
		$result = $this->db->order_by('c.id')->group_by('a.role_id')->get()->result_object();

		return [
			$result,
			($start != '') ? 'Dari tanggal '.dateIDFormatShort($start).' s.d. '.dateIDFormatShort($end) : 'Seluruh waktu',
			$this->getAccountById($account)
		];
	}
}
