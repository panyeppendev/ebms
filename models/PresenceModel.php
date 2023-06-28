<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PresenceModel extends CI_Model
{
	public function getRombel($level, $kelas)
	{
		$period = $this->dm->getperiod();
		return $this->db->order_by('rombel', 'ASC')->get_where('homerooms', ['class' => $kelas, 'level' => $level, 'period' => $period])->result_object();
	}

	public function loadData()
	{
		$level = $this->input->post('level', true);
		$grade = $this->input->post('grade', true);
		$rombel = $this->input->post('rombel', true);
		$period = $this->dm->getperiod();

		if ($level == '' || $grade == '' || $rombel == ''){
			return '';
		}

		$this->db->select('a.*');
		$this->db->select('SUM(IF(b.presence = "a", b.amount, 0)) AS a');
		$this->db->select('SUM(IF(b.presence = "i", b.amount, 0)) AS i');
		$this->db->select('SUM(IF(b.presence = "s", b.amount, 0)) AS s');
		$this->db->from('presences AS b')->join('students AS a', 'a.id = b.student_id');
		$this->db->where([
			'b.period' => $period,
			'b.level' => $level,
			'b.grade' => $grade,
			'b.rombel' => $rombel
		]);
		return $this->db->group_by('b.student_id')->get()->result_object();
	}

	public function loadAdd()
	{
		$level = $this->input->post('level', true);
		$grade = $this->input->post('grade', true);
		$rombel = $this->input->post('rombel', true);

		return $this->db->get_where('students', [
			'status' => 'AKTIF', 'class' => $grade, 'level' => $level, 'rombel' => $rombel
		])->result_object();
	}

	public function save()
	{
		$month = $this->input->post('month', true);
		$level = $this->input->post('level', true);
		$grade = $this->input->post('grade', true);
		$rombel = $this->input->post('rombel', true);
		$a = $this->input->post('a', true);
		$i = $this->input->post('i', true);
		$s = $this->input->post('s', true);
		$period = $this->dm->getperiod();

		if ($month == ''){
			return [
				'status' => 400,
				'message' => 'Pastikan bulan sudah dipilih'
			];
		}

		if ($level == '' || $grade == '' || $rombel == ''){
			return [
				'status' => 400,
				'message' => 'Ada yang tidak valid. Coba refresh halaman'
			];
		}

		$rows = 0;

		foreach ($a as $key => $value) {
			$check = $this->db->get_where('presences', [
				'student_id' => $key,
				'level' => $level,
				'grade' => $grade,
				'rombel' => $rombel,
				'presence' => 'a',
				'month' => $month,
				'period' => $period
			])->row_object();

			if ($value > 0 && $value != '') {
				if (!$check) {
					$this->db->insert('presences', [
						'student_id' => $key,
						'level' => $level,
						'grade' => $grade,
						'rombel' => $rombel,
						'presence' => 'a',
						'month' => $month,
						'amount' => $value,
						'period' => $period
					]);

				}else{
					$this->db->where('id', $check->id)->update('presences', [
						'amount' => $check->amount + $value
					]);
				}
				if ($this->db->affected_rows() > 0){
					$rows++;
				}

			}
		}

		foreach ($i as $key => $value) {
			$check = $this->db->get_where('presences', [
				'student_id' => $key, 'level' => $level, 'grade' => $grade,
				'rombel' => $rombel, 'presence' => 'i', 'month' => $month, 'period' => $period
			])->row_object();

			if ($value > 0 && $value != '') {
				if (!$check) {
					$this->db->insert('presences', [
						'student_id' => $key,
						'level' => $level,
						'grade' => $grade,
						'rombel' => $rombel,
						'presence' => 'i',
						'month' => $month,
						'amount' => $value,
						'period' => $period
					]);

				}else{
					$this->db->where('id', $check->id)->update('presences', [
						'amount' => $check->amount + $value
					]);
				}
				if ($this->db->affected_rows() > 0){
					$rows++;
				}

			}
		}

		foreach ($s as $key => $value) {
			$check = $this->db->get_where('presences', [
				'student_id' => $key, 'level' => $level, 'grade' => $grade, 'rombel' => $rombel,
				'presence' => 's', 'month' => $month, 'period' => $period
			])->row_object();

			if ($value > 0 && $value != '') {
				if (!$check) {
					$this->db->insert('presences', [
						'student_id' => $key,
						'level' => $level,
						'grade' => $grade,
						'rombel' => $rombel,
						'presence' => 's',
						'month' => $month,
						'amount' => $value,
						'period' => $period
					]);

				}else{
					$this->db->where('id', $check->id)->update('presences', [
						'amount' => $check->amount + $value
					]);
				}
				if ($this->db->affected_rows() > 0){
					$rows++;
				}

			}
		}

		return [
			'status' => 200,
			'message' => $rows.' data berhasil ditambahkan'
		];
	}
}
