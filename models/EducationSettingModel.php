<?php
defined('BASEPATH') or exit('No direct script access allowed');
class EducationSettingModel extends CI_Model
{
	public function saveSet()
	{
		$kelas = $this->input->post('kelas', true);
		$level = $this->input->post('level', true);
		$rombel = $this->input->post('rombel', true);
		$head = $this->input->post('head', true);
		if ($kelas == '' || $level == '' || $rombel == '' || $rombel <= 0 || $head == ''){
			return [
				'status' => 400,
				'message' => 'Pastikan semua inputan sudah diisi'
			];
		}

		$period = $this->dm->getperiod();
		$check = $this->db->get_where('homerooms', [
			'class' => $kelas, 'rombel' => $rombel, 'level' => $level, 'period' => $period
		])->row_object();
		if ($check) {
			$this->db->where('id', $check->id)->update('homerooms', [
				'teacher' => strtoupper($head)
			]);
			if ($this->db->affected_rows() > 0){
				return [
					'status' => 200,
					'message' => 'Satu data berhasil ditimpa'
				];
			}

			return [
				'status' => 400,
				'message' => 'Kesalahan server'
			];
		}

		$this->db->insert('homerooms', [
			'teacher' => strtoupper($head),
			'class' => $kelas,
			'rombel' => $rombel,
			'level' => $level,
			'period' => $period
		]);
		if ($this->db->affected_rows() > 0){
			return [
				'status' => 200,
				'message' => 'Satu data berhasil ditambahkan'
			];
		}

		return [
			'status' => 400,
			'message' => 'Kesalahan server'
		];
	}

	public function getRombel($level, $kelas)
	{
		$period = $this->dm->getperiod();
		return $this->db->order_by('rombel', 'ASC')->get_where('homerooms', ['class' => $kelas, 'level' => $level, 'period' => $period])->result_object();
	}

	public function loadData()
	{
		$period = $this->dm->getperiod();
		$kelas = $this->input->post('kelas', true);
		$level = $this->input->post('level', true);
		$this->db->select('*')->from('homerooms')->where([
			'level' => $level, 'period' => $period
		]);
		if ($kelas){
			$this->db->where('class', $kelas);
		}
		return $this->db->get()->result_object();
	}

	public function getAdd()
	{
		$kelas = $this->input->post('kelas', true);
		$rombel = $this->input->post('rombel', true);
		$level = $this->input->post('level', true);

		return $this->db->get_where('students', [
			'level' => $level, 'class' => $kelas, 'rombel !=' => $rombel
		])->result_object();
	}

	public function saveSetMurid()
	{
		$id = $this->input->post('id', true);
		$rombel = $this->input->post('rombel', true);
		$rows = 0;
		for ($i=0; $i < sizeof($id); $i++) {
			$this->db->where('id', $id[$i])->update('students', ['rombel' => $rombel]);
			if ($this->db->affected_rows() > 0) {
				$rows++;
			}
		}

		if ($rows <= 0) {
			return [
				'status' => 400,
				'message' => 'Tak ada data yang berhasil diubah'
			];
		}

		return [
			'status' => 200,
			'message' => $rows.' data berhasil diunah'
		];
	}

	public function loadRombel()
	{
		$level = $this->input->post('level', true);
		$grade = $this->input->post('grade', true);
		$rombel = $this->input->post('rombel', true);

		if ($rombel != '') {
			return $this->db->get_where('students', [
				'level' => $level,
				'class' => $grade,
				'rombel' => $rombel,
				'status' => 'AKTIF'
			])->result_object();
		}

		return '';
	}
}
