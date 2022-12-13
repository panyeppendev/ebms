<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CheckinModel extends CI_Model
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

    public function loaddata()
    {
        $domicile = $this->input->post('domicile', true);

        $this->db->select('a.created_at, b.*')->from('students AS b');
        $this->db->join('holidays AS a', 'b.id = a.student_id', 'left');
        $this->db->where('b.status', 'AKTIF');
        if ($domicile) {
            $this->db->where('b.domicile', $domicile);
        }
        return $this->db->order_by('a.id', 'DESC')->get()->result_object();
    }

    public function checkCheckin($nis, $period, $holiday)
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

        //CHECK CARD
        $checkCard = $this->db->get_where('cards', ['id' => $nis])->row_object();
        if (!$checkCard) {
            return [
                'status' => 400,
                'message' => 'Kartu tidak valid'
            ];
        }

        $statusCard = $checkCard->status;
        if ($statusCard != 'ACTIVE') {
            $statusText = ['INACTIVE' => 'belum diaktivasi', 'BLOCKED' => 'sudah diblokir'];
            return [
                'status' => 400,
                'message' => 'Kartu ini ' . $statusText[$statusCard]
            ];
        }

        $nis = $checkCard->student_id;

        $cekStudent = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();
        if (!$cekStudent) {
            return [
                'status' => 400,
                'message' => 'Data Santri tidak ditemukan'
            ];
        }

        $checkCheckin = $this->checkCheckin($nis, $period, $holiday);
        if ($checkCheckin > 0) {
            return [
                'status' => 400,
                'message' => 'Santri sudah check in sebelumnya'
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

        $checkRequirementDetail = $this->db->get_where('requirement_detail', [
            'requirement_id' => $IDRequirement
        ])->num_rows();

        if ($checkRequirementDetail <= 0) {
            $getAccount = $this->db->get_where('security_account', [
                'status' => 'CHECKED'
            ])->result_object();
            if ($getAccount) {
                $data = [];
                foreach ($getAccount as $d) {
                    $data[] = [
                        'requirement_id' => $IDRequirement,
                        'security_account' => $d->id,
                        'status' => 'NO'
                    ];
                }
                $this->db->insert_batch('requirement_detail', $data);
            }
        }

        $syarat = $this->db->get_where('requirement_detail', [
            'requirement_id' => $IDRequirement, 'status' => 'NO'
        ])->num_rows();

        if ($syarat <= 0) {
            $this->db->insert('holidays', [
                'student_id' => $nis,
                'period' => $period,
                'holiday' => $holiday,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'requirement' => $IDRequirement,
            'kelas' => $cekStudent->class,
            'syarat' => $syarat
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
