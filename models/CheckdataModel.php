<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CheckdataModel extends CI_Model
{
    public function getSetting()
    {
        $get = $this->db->get('security_setting')->row_object();
        if (!$get || $get->status == 'OPEN') {
            return 'OPEN';
        } else {
            return 'CLOSE';
        }
    }

    public function showSetting()
    {
        $get = $this->db->get('security_setting')->row_object();
        if ($get) {
            return [
                $get->holiday,
                $get->gohome,
                datetimeIDFormat($get->gohome),
            ];
        } else {
            return [
                'Belum diatur',
                'Belum diatur',
                'Belum diatur'
            ];
        }
    }


    public function checkHolidays($nis, $period, $holiday)
    {
        return $this->db->get_where('holidays', [
            'student_id' => $nis, 'period' => $period, 'holiday' => $holiday
        ])->num_rows();
    }

    public function checkNIS()
    {
        $nis = str_replace('_', '', $this->input->post('nis', true));
        $period = $this->dm->getperiod();
        $holiday = $this->showSetting()[0];

        $cekStudent = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();
        if (!$cekStudent) {
            return [
                'status' => 400,
                'message' => 'Data Santri tidak ditemukan'
            ];
        }

        $getRequirement = $this->db->get_where('requirements', [
            'student_id' => $nis, 'period' => $period, 'holiday' => $holiday
        ])->row_object();
        if (!$getRequirement) {
            return [
                'status' => 400,
                'message' => 'Santri ini belum terdaftar di DAFTAR SANTRI LIBUR'
            ];
        }

        $IDRequirement = $getRequirement->id;

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'requirement' => $IDRequirement,
            'kelas' => $cekStudent->class,
            'syarat' => $this->checkHolidays($nis, $period, $holiday)
        ];
    }

    public function getData()
    {
        $nis = $this->input->post('nis', true);
        $requirement = $this->input->post('requirement', true);

        $this->db->select('a.id, a.status, b.name, b.id AS account')->from('requirement_detail AS a');
        $this->db->join('security_account AS b', 'a.security_account = b.id');
        $this->db->where('requirement_id', $requirement);
        $requirements = $this->db->order_by('a.status', 'ASC')->get()->result_object();

        $totalRequirement = $this->db->get_where('requirement_detail', [
            'requirement_id' => $requirement
        ])->num_rows();

        $totalYes = $this->db->get_where('requirement_detail', [
            'requirement_id' => $requirement, 'status' => 'YES'
        ])->num_rows();

        return [
            $this->db->get_where('students', ['id' => $nis])->row_object(),
            $requirements,
            $totalRequirement,
            $totalYes
        ];
    }

    public function checkin()
    {
        $nis = $this->input->post('nis', true);
        $requirement = $this->input->post('requirement', true);

        $this->db->select('a.id, a.status, b.name, b.id AS account')->from('requirement_detail AS a');
        $this->db->join('security_account AS b', 'a.security_account = b.id');
        $this->db->where('requirement_id', $requirement);
        return $this->db->order_by('b.id', 'ASC')->get()->result_object();
    }
}
