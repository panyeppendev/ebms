<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CardModel extends CI_Model
{
    public function checkid()
    {
        $id = str_replace('_', '', $this->input->post('id', true));
        $check = $this->db->get_where('cards', ['id' => $id])->row_object();
        if (!$check) {
            return [
                'status' => 400,
                'message' => 'Kartu tidak valid'
            ];
        }

        $nis = $check->student_id;
        $status = $check->status;

        return [
            'status' => 200,
            'id' => $id,
            'nis' => $nis,
            'message' => $status
        ];
    }

    public function getData()
    {
        $id = $this->input->post('id', true);
        $nis = $this->input->post('nis', true);
        $message = $this->input->post('message', true);

        $getStudent = $this->db->get_where('students', ['id' => $nis])->row_object();
        if (!$getStudent) {
            $status = 400;
            $getStudent = 0;
        } else {
            $status = 200;
            $getStudent;
        }

        return [
            'status' => $status,
            'student' => $getStudent,
            'id' => $id,
            'nis' => $nis,
            'message' => $message
        ];
    }

    public function save()
    {
        $id = $this->input->post('id', true);
        $nis = $this->input->post('nis', true);

        $checkCard = $this->db->get_where('cards', ['id' => $id])->row_object();
        if (!$checkCard) {
            return [
                'status' => 400,
                'message' => 'Kartu tidak valid'
            ];
        }

        $student = $checkCard->student_id;
        $status = $checkCard->status;
        if ($nis != $student) {
            return [
                'status' => 400,
                'message' => 'Kartu tidak valid'
            ];
        }

        if ($status != 'BLOCKED') {
            $message = [
                'ACTIVE' => 'sedang aktif',
                'INACTIVE' => 'belum diaktivasi'
            ];
            return [
                'status' => 400,
                'message' => 'Kartu ini ' . $message[$status]
            ];
        }

        $checkOtherCard = $this->db->get_where('cards', [
            'student_id' => $nis, 'id !=' => $id, 'status !=' => 'BLOCKED'
        ])->num_rows();
        if ($checkOtherCard > 0) {
            return [
                'status' => 400,
                'message' => 'Ada kartu lain yang masih aktif atau belum diaktivasi'
            ];
        }

        $this->db->where('id', $id)->update('cards', ['status' => 'ACTIVE']);
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal saat mencoba menyimpan'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }
}
