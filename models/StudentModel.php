<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StudentModel extends CI_Model
{
    public function getData()
    {
        $period = $this->input->post('period', true);
        $name = $this->input->post('name', true);
        $domicile = $this->input->post('domicile', true);

        $this->db->select('*')->from('students');
        if ($period != '') {
            $this->db->where('period', $period);
        }

        if ($domicile != '') {
            $this->db->where('domicile', $domicile);
        }

        if ($name != '') {
            $this->db->like('name', $name);
        }
        $data = $this->db->order_by('id', 'ASC')->get()->result_object();

        $this->db->select('*')->from('students');
        if ($period != '') {
            $this->db->where('period', $period);
        }
        if ($domicile != '') {
            $this->db->where('domicile', $domicile);
        }
        if ($name != '') {
            $this->db->like('name', $name);
        }
        $amount = $this->db->get()->num_rows();

        return [$data, $amount];
    }

    public function detailData($id)
    {
        return $this->db->get_where('students', ['id' => $id])->row_object();
    }

    public function idGenerator($year)
    {
        $cek = $this->db->order_by('id', 'DESC')->get_where('students', [
            'year_of_entry' => $year
        ])->row_object();
        if ($cek) {
            $lastId = $cek->id;
            $result = $lastId + 1;
        } else {
            //$getSubYear = substr($year, 2, 2);
            // $result = $getSubYear . '00001';
            $result = $year . '0197001';
        }

        return $result;
    }

    public function save()
    {
        $id = $this->input->post('id', true);
        $phone = str_replace('-', '', $this->input->post('phone', true));
        $date = $this->input->post('date_of_birth', true);
        $month = $this->input->post('month_of_birth', true);
        $year = $this->input->post('year_of_birth', true);
        $dateEntry = $this->input->post('date_of_entry', true);
        $monthEntry = $this->input->post('month_of_entry', true);
        $yearEntry = $this->input->post('year_of_entry', true);
        $dateEntryHijri = $this->input->post('date_of_entry_hijriah', true);
        $monthEntryHijri = $this->input->post('month_of_entry_hijriah', true);
        $yearEntryHijri = $this->input->post('year_of_entry_hijriah', true);
        $date_of_entry = $yearEntry . '-' . $monthEntry . '-' . $dateEntry;
        $date_of_entry_hijriah = $yearEntryHijri . '-' . $monthEntryHijri . '-' . $dateEntryHijri;
        $date_of_birth = $year . '-' . $month . '-' . $date;
        $idGenerated = $this->idGenerator($yearEntryHijri);
        $period = $this->input->post('period', true);

        $dataStudent = [
            'id' => $idGenerated,
            'year_of_entry' => $yearEntryHijri,
            'date_of_entry' => $date_of_entry,
            'date_of_entry_hijriah' => $date_of_entry_hijriah,
            'period' => $period,
            'nik' => $this->input->post('nik', true),
            'kk' => $this->input->post('kk', true),
            'name' => strtoupper($this->input->post('name', true)),
            'place_of_birth' => ucwords($this->input->post('place_of_birth', true)),
            'date_of_birth' => $date_of_birth,
            'last_education' => $this->input->post('last_education', true),
            'address' => ucwords($this->input->post('address', true)),
            'village' => $this->input->post('village', true),
            'district' => $this->input->post('district', true),
            'city' => $this->input->post('city', true),
            'province' => $this->input->post('province', true),
            'postal_code' => $this->input->post('postal_code', true),
            'father_nik' => $this->input->post('father_nik', true),
            'father' => strtoupper($this->input->post('father', true)),
            'mother_nik' => $this->input->post('mother_nik', true),
            'mother' => strtoupper($this->input->post('mother', true)),
            'phone' => $phone,
            // 'status_of_domicile' => $this->input->post('status_of_domicile', true),
            'status_of_domicile' => 'Asrama',
            'domicile' => $this->input->post('domicile', true),
            'class' => $this->input->post('class', true),
            'level' => $this->input->post('level', true),
            'class_of_formal' => $this->input->post('class_of_formal', true),
            'level_of_formal' => $this->input->post('level_of_formal', true),
            'status' => 'AKTIF',
            'user_id' => $this->session->userdata('user_id')
        ];

        $dataRegistration = [
            'student_id' => $idGenerated,
            'period' => $this->input->post('period', true),
            'status_of_domicile' => 'Asrama',
            // 'status_of_domicile' => $this->input->post('status_of_domicile', true),
            'domicile' => $this->input->post('domicile', true),
            'class' => $this->input->post('class', true),
            'rombel' => 'A',
            'level' => $this->input->post('level', true),
            'class_of_formal' => $this->input->post('class_of_formal', true),
            'rombel_of_formal' => 'A',
            'level_of_formal' => $this->input->post('level_of_formal', true)
        ];

        if ($id == 0) {
            $this->db->insert('students', $dataStudent);
            $this->db->insert('registrations', $dataRegistration);
            $result = [
                'status' => 200,
                'type' => 'ENTRI',
                'id' => 0
            ];
        } else {
            array_shift($dataStudent);
            array_shift($dataRegistration);
            $this->db->where('id', $id)->update('students', $dataStudent);
            $this->db->where(['id' => $id, 'period' => $period])->update('registrations', $dataRegistration);
            $result = [
                'status' => 200,
                'type' => 'EDIT',
                'id' => $id
            ];
        }

        return $result;
    }

    public function cekid()
    {
        $id = $this->input->post('id', true);
        $cekid = $this->db->get_where('students', ['id' => $id])->row_object();
        if ($cekid) {
            $result = [
                'status' => 200,
                'data' => $cekid
            ];
        } else {
            $result = [
                'status' => 400,
                'data' => []
            ];
        }

        return $result;
    }

    public function print($domicile)
    {
        return $this->db->get_where('students', [
            'domicile' => $domicile, 'status' => 'AKTIF'
        ])->result_object();
    }

    public function getPrint($id)
    {
        return $this->db->get_where('students', ['id' => $id])->row_object();
    }

    public function getCard($id)
    {
        $data = $this->db->order_by('created_at', 'DESC')->get_where('cards', [
            'student_id' => $id, 'status !=' => 'BLOCKED'
        ])->row_object();

        return $data;
    }

    public function makeCard()
    {
        $id = $this->input->post('id', true);
        $date = date('Y-m-d');

        $checkStudent = $this->db->get_where('students', [
            'id' => $id, 'status' => 'AKTIF'
        ])->num_rows();

        $checkCard = $this->db->get_where('cards', [
            'student_id' => $id, 'status !=' => 'BLOCKED'
        ])->num_rows();

        $checkCardInactive = $this->db->get_where('cards', [
            'student_id' => $id, 'created_at' => $date
        ])->num_rows();

        if ($checkStudent <= 0) {
            return [
                'status' => 400,
                'message' => 'Santri ini tidak ditemukan'
            ];
        }

        if ($checkCard > 0) {
            return [
                'status' => 400,
                'message' => 'Kartu sudah dibuat sebelumnya'
            ];
        }

        if ($checkCardInactive > 0) {
            return [
                'status' => 400,
                'message' => 'Anda tidak bisa membuat dua kartu sama dalam satu hari'
            ];
        }

        $now = date('Ymd');
        $this->db->insert('cards', [
            'id' => $id . $now,
            'student_id' => $id,
            'created_at' => $date,
            'status' => 'INACTIVE'
        ]);
        if ($this->db->affected_rows() > 0) {
            return ['status' => 200];
        } else {
            return [
                'status' => 400,
                'message' => 'Kesalahan server'
            ];
        }
    }

    public function activeCard()
    {
        $id = $this->input->post('id', true);
        $this->db->where('id', $id)->update('cards', ['status' => 'ACTIVE']);
        if ($this->db->affected_rows() > 0) {
            return [
                'status' => 200,
                'message' => 'Kesalahan server'
            ];
        } else {
            return ['status' => 200];
        }
    }

    public function blockCard()
    {
        $id = $this->input->post('id', true);
        $this->db->where('id', $id)->update('cards', ['status' => 'BLOCKED']);
        if ($this->db->affected_rows() > 0) {
            return [
                'status' => 200,
                'message' => 'Kesalahan server'
            ];
        } else {
            return ['status' => 200];
        }
    }

    public function getNisFromCard($card)
    {
        $data = $this->db->get_where('cards', ['id' => $card])->row_object();

        return $data->student_id;
    }

	public function destroy()
	{
		$id = $this->input->post('id', true);
		$check = $this->db->get_where('students', ['id' => $id])->num_rows();
		if ($check <= 0) {
			return [
				'status' => 400,
				'message' => 'Data tidak valid'
			];
		}

		$this->db->where('id', $id)->delete('students');
		if ($this->db->affected_rows() <= 0){
			return [
				'status' => 400,
				'message' => 'Server gagal memproses permintaan'
			];
		}

		return [
			'status' => 200,
			'message' => 'Data tidak valid'
		];
	}
}
