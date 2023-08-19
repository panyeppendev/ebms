<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SecurityReportModel extends CI_Model
{
    public function dataBarber($date)
    {
        $this->db->select('a.*, b.name, b.domicile')->from('payment_security as a');
		$this->db->join('students as b', 'b.id = a.student_id');
		$this->db->where(['DATE(a.created_at)' => $date, 'a.type' => 'BARBER']);
		return $this->db->order_by('a.created_at', 'ASC')->get()->result_object();
    }

    public function dataPenalty($start, $end)
    {
		$period = $this->dm->getperiod();

		$this->db->select('a.*, b.name, b.domicile, b.father, b.class, b.class_of_formal, b.level_of_formal, b.village, b.city, c.name as constitution, c.type, c.category, c.clause');
		$this->db->from('punishments as a')->join('students as b', 'b.id = a.student_id');
		$this->db->join('constitutions as c', 'c.id = a.constitution_id');
		$this->db->where('a.period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		return $this->db->order_by('a.created_at', 'ASC')->get()->result_object();
    }

	public function laporanPerizinan($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) AS total, b.domicile')->from('permissions as a');
		$this->db->join('students as b', 'a.student_id = b.id');
		$this->db->where('a.period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$data = $this->db->group_by('b.domicile')->order_by('total', 'DESC')->get()->result_object();

		$this->db->select('COUNT(id) AS total')->from('permissions');
		$this->db->where('period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(created_at) >=', $start);
			$this->db->where('DATE(created_at) <=', $end);
		}
		$total = $this->db->get()->row_object();
//		return $this->db->last_query();
		return [$data, $total];
	}

	public function laporanAlasan($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('reason, COUNT(id) AS total')->from('permissions');
		$this->db->where('period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(created_at) >=', $start);
			$this->db->where('DATE(created_at) <=', $end);
		}
		$total = $this->db->group_by('reason')->order_by('total', 'DESC')->get()->result_object();
//		return $this->db->last_query();
		return $total;
	}

	public function tenTop($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) AS total, b.name, b.domicile')->from('permissions as a');
		$this->db->join('students as b', 'a.student_id = b.id');
		$this->db->where(['a.period' => $period, 'type' => 'LONG']);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$long = $this->db->group_by('a.student_id')->order_by('total', 'DESC')->limit(10)->get()->result_object();

		$this->db->select('COUNT(a.id) AS total, b.name, b.domicile')->from('permissions as a');
		$this->db->join('students as b', 'a.student_id = b.id');
		$this->db->where(['a.period' => $period, 'type' => 'SHORT']);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$short = $this->db->group_by('a.student_id')->order_by('total', 'DESC')->limit(10)->get()->result_object();
//		return $this->db->last_query();
		return [$long, $short];
	}

	public function laporanPayment($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('type, SUM(amount) AS total')->from('payment_security');
		$this->db->where('period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(created_at) >=', $start);
			$this->db->where('DATE(created_at) <=', $end);
		}
		$total = $this->db->group_by('type')->get()->result_object();
//		return $this->db->last_query();
		return $total;
	}

	public function laporanLate($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) AS total, b.domicile')->from('permissions as a');
		$this->db->join('students as b', 'a.student_id = b.id');
		$this->db->where(['a.period' => $period, 'a.status' => 'LATE']);
		$this->db->or_where('a.status', 'LATE-DONE');
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$data = $this->db->group_by('b.domicile')->order_by('total', 'DESC')->get()->result_object();

		$this->db->select('COUNT(id) AS total')->from('permissions');
		$this->db->where(['period' => $period, 'status' => 'LATE']);
		$this->db->or_where('status', 'LATE-DONE');
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(created_at) >=', $start);
			$this->db->where('DATE(created_at) <=', $end);
		}
		$total = $this->db->get()->row_object();
//		return $this->db->last_query();
		return [$data, $total];
	}

	public function laporanPelanggaran($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) as total, b.domicile');
		$this->db->from('punishments AS a')->join('students AS b', 'b.id = a.student_id');
		$this->db->where([
			'a.period' => $period
		]);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$data = $this->db->group_by('b.domicile')->get()->result_object();
		$result = [];
		if ($data) {
			foreach ($data as $d) {
				$result[] = [
					'domicile' => $d->domicile,
					'low' => $this->getCategory($d->domicile, $start, $end, 'LOW'),
					'medium' => $this->getCategory($d->domicile, $start, $end, 'MEDIUM'),
					'high' => $this->getCategory($d->domicile, $start, $end, 'HIGH'),
					'top' => $this->getCategory($d->domicile, $start, $end, 'TOP'),
					'total' => $d->total
				];
			}
		}else{
			$result = [];
		}

		$this->db->select('COUNT(id) AS total')->from('punishments');
		$this->db->where(['period' => $period]);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(created_at) >=', $start);
			$this->db->where('DATE(created_at) <=', $end);
		}
		$total = $this->db->get()->row_object();

		$low = $this->countCategory($start, $end, 'LOW');
		$medium = $this->countCategory($start, $end, 'MEDIUM');
		$high = $this->countCategory($start, $end, 'HIGH');
		$top = $this->countCategory($start, $end, 'TOP');
		return [$result, $total, $low, $medium, $high, $top];
	}

	public function getCategory($domicile, $start, $end, $category)
	{
		$this->db->select('COUNT(a.id) as total')->from('punishments as a');
		$this->db->join('students as b', 'b.id = a.student_id');
		$this->db->join('constitutions as c', 'c.id = a.constitution_id');
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$this->db->where(['b.domicile' => $domicile, 'c.category' => $category]);
		$data = $this->db->get()->row_object();
		if ($data->total) {
			return $data->total;
		}else{
			return 0;
		}
	}

	public function countCategory($start, $end, $category)
	{
		$period = $this->dm->getperiod();
		$this->db->select('COUNT(a.id) as total')->from('punishments as a');
		$this->db->join('constitutions as c', 'c.id = a.constitution_id');
		$this->db->where('a.period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$this->db->where(['c.category' => $category]);
		$data = $this->db->get()->row_object();
		if ($data->total) {
			return $data->total;
		}else{
			return 0;
		}
	}

	public function orderPelanggaran($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) as total, b.name, b.category')->from('punishments as a');
		$this->db->join('constitutions as b', 'b.id = a.constitution_id');
		$this->db->where('a.period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$this->db->group_by('a.constitution_id')->order_by('total', 'DESC')->limit(10);
		return $this->db->get()->result_object();
	}

	public function pelanggaranSantri($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) as total, b.name, b.domicile')->from('punishments as a');
		$this->db->join('students as b', 'b.id = a.student_id');
		$this->db->where('a.period', $period);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$this->db->group_by('a.student_id')->order_by('total', 'DESC')->limit(30);
		return $this->db->get()->result_object();
	}

	public function skorsing($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) AS total, b.domicile')->from('suspensions as a');
		$this->db->join('students as b', 'a.student_id = b.id');
		$this->db->where(['a.period' => $period, 'a.status !=' => 'INACTIVE']);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$data = $this->db->group_by('b.domicile')->order_by('total', 'DESC')->get()->result_object();

		$this->db->select('COUNT(id) AS total')->from('suspensions');
		$this->db->where(['period' => $period, 'status !=' => 'INACTIVE']);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(created_at) >=', $start);
			$this->db->where('DATE(created_at) <=', $end);
		}
		$total = $this->db->get()->row_object();
//		return $this->db->last_query();
		return [$data, $total];
	}

	public function skorsingPelanggaran($start, $end)
	{
		$period = $this->dm->getperiod();

		$this->db->select('COUNT(a.id) AS total, b.name')->from('suspensions as a');
		$this->db->join('constitutions as b', 'a.constitution_id = b.id');
		$this->db->where(['a.period' => $period, 'a.status !=' => 'INACTIVE']);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(a.created_at) >=', $start);
			$this->db->where('DATE(a.created_at) <=', $end);
		}
		$data = $this->db->group_by('b.id')->order_by('total', 'DESC')->get()->result_object();

		$this->db->select('COUNT(id) AS total')->from('suspensions');
		$this->db->where(['period' => $period, 'status !=' => 'INACTIVE']);
		if ($start !== '0' && $end !== '0') {
			$this->db->where('DATE(created_at) >=', $start);
			$this->db->where('DATE(created_at) <=', $end);
		}
		$total = $this->db->get()->row_object();
//		return $this->db->last_query();
		return [$data, $total];
	}

}
