<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeModel extends CI_Model
{
    public function step()
    {
        $data = $this->db->get_where('steps', ['status' => 'PAYMENT'])->row_object();
        if (!$data) {
            return 0;
        }

        return $data->step;
    }

    public function getdata()
    {
        $step = $this->step();
        $period = $this->dm->getperiod();

        $this->db->select('COUNT(id) AS amount')->from('students')->where('status', 'AKTIF');
        $student = $this->db->get()->row_object();

        $this->db->select('COUNT(id) AS amount, domicile')->from('students')->where('status', 'AKTIF');
        $students = $this->db->order_by('domicile', 'ASC')->group_by('domicile')->get()->result_object();

        $package = $this->db->select('COUNT(id) AS amount, package')->from('packages')->where([
            'step' => $step, 'period' => $period
        ])->group_by('package')->order_by('package', 'ASC')->get()->result_object();

        $this->db->select('b.domicile');
        $this->db->select('SUM(CASE WHEN a.package = "A" THEN 1 ELSE 0 END) AS A');
        $this->db->select('SUM(CASE WHEN a.package = "B" THEN 1 ELSE 0 END) AS B');
        $this->db->select('SUM(CASE WHEN a.package = "C" THEN 1 ELSE 0 END) AS C');
        $this->db->select('SUM(CASE WHEN a.package = "D" THEN 1 ELSE 0 END) AS D');
        $this->db->select('SUM(CASE WHEN a.package = "UNKNOWN" THEN 1 ELSE 0 END) AS Tidak');
        $this->db->from('packages AS a')->join('students AS b', 'b.id = a.student_id');
        $this->db->where([
            'a.step' => $step, 'a.period' => $period
        ]);
        $packages = $this->db->group_by('b.domicile')->get()->result_object();

        return [
            $student,
            $students,
            $package,
            $packages
        ];
    }
}
