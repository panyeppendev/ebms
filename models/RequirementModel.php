<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RequirementModel extends CI_Model
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

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'requirement' => $IDRequirement,
            'kelas' => $cekStudent->class
        ];
    }

    public function getData()
    {
        $nis = $this->input->post('nis', true);
        $requirement = $this->input->post('requirement', true);

        $totalRequirement = $this->db->get_where('requirement_detail', [
            'requirement_id' => $requirement
        ])->num_rows();

        $totalYes = $this->db->get_where('requirement_detail', [
            'requirement_id' => $requirement, 'status' => 'YES'
        ])->num_rows();

        return [
            $this->db->get_where('students', ['id' => $nis])->row_object(),
            $totalRequirement,
            $totalYes
        ];
    }

    public function checkin()
    {
        $requirement = $this->input->post('requirement', true);

        $this->db->select('a.id, a.status, b.name, b.id AS account')->from('requirement_detail AS a');
        $this->db->join('security_account AS b', 'a.security_account = b.id');
        $this->db->where('requirement_id', $requirement);
        return $this->db->order_by('b.id', 'ASC')->get()->result_object();
    }

    public function saveCheckin()
    {
        $requirement = $this->input->post('id_requirement', true);
        $status = $this->input->post('status');
        $data = [];
        foreach ($status as $key => $value) {
            $data[] = [
                'id' => $key,
                'status' => $value
            ];
        }
        $this->db->update_batch('requirement_detail', $data, 'id');
        $result = $this->db->affected_rows();
        if ($result <= 0) {
            return [
                'status' => 400,
                'message' => 'Tidak ada data yang diubah'
            ];
        }

        $this->db->where('id', $requirement)->update('requirements', [
            'updated_at' => date('Y-m-d H:i:s'), 'status' => 'CHECKED'
        ]);
        return [
            'status' => 200,
            'message' => $result
        ];
    }
}
