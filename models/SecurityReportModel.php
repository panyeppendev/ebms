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
}
