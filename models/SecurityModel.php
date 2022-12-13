<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SecurityModel extends CI_Model
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
                'Libur ' . $get->holiday,
                datetimeIDFormat($get->gohome),
                datetimeIDFormat($get->comeback)
            ];
        } else {
            return [
                'Klik & atur sekarang',
                'Belum diatur',
                'Belum diatur'
            ];
        }
    }

    public function loadData()
    {
        return $this->db->get('security_account')->result_object();
    }

    public function save()
    {
        $id = $this->input->post('id', true);
        $name = $this->input->post('name', true);
        if ($name == '') {
            return ['status' => 400, 'message' => 'Nama akun pastikan tidak kosong'];
        }

        if ($id != 0) {
            $this->db->where('id', $id)->update('security_account', ['name' => ucfirst($name)]);
            if ($this->db->affected_rows() <= 0) {
                return [
                    'status' => 400,
                    'message' => 'Kesalahan server'
                ];
            }

            return [
                'status' => 200,
                'message' => 'Satu data berhasil diedit',
                'type' => 'EDIT'
            ];
        } else {
            $this->db->insert('security_account', [
                'name' => ucfirst($name), 'status' => 'UNCHECKED'
            ]);
            if ($this->db->affected_rows() <= 0) {
                return ['status' => 400, 'message' => 'Kesalahan server'];
            }

            return [
                'status' => 200,
                'message' => 'Satu data berhasil ditambahkan',
                'type' => 'ADD'
            ];
        }
    }

    public function delete()
    {
        $id = $this->input->post('id', true);
        $this->db->where('id', $id)->delete('security_account');
        if ($this->db->affected_rows() <= 0) {
            return ['status' => 400, 'message' => 'Kesalahan server'];
        }

        return ['status' => 200, 'message' => 'Sukses'];
    }

    public function changeStatus()
    {
        $id = $this->input->post('id', true);

        //GET DATA
        $check = $this->db->get_where('security_account', ['id' => $id])->row_object();
        if (!$check) {
            return ['status' => 400, 'message' => 'Data tidak valid'];
        }

        $status = $check->status;
        if ($status == 'CHECKED') {
            $data = ['status' => 'UNCHECKED'];
        } else {
            $data = ['status' => 'CHECKED'];
        }
        $this->db->where('id', $id)->update('security_account', $data);

        if ($this->db->affected_rows() <= 0) {
            return ['status' => 400, 'message' => 'Kesalahan server'];
        }

        return ['status' => 200, 'message' => 'Sukses'];
    }

    public function getData($id)
    {
        $check = $this->db->get_where('security_account', ['id' => $id])->row_object();
        if (!$check) {
            return ['status' => 400, 'name' => ''];
        }
        return ['status' => 200, 'name' => $check->name];
    }

    public function saveSetting()
    {
        $holiday = $this->input->post('holiday', true);
        $gohome = $this->input->post('gohome', true);
        $comeback = $this->input->post('comeback', true);

        if ($holiday == '' || $gohome == '' || $comeback == '') {
            return [
                'status' => 400,
                'message' => 'Pastikan semua bidang sudah diisi lengkap',
                'satu' => $holiday,
                'dua' => $gohome,
                'tiga' => $comeback
            ];
        }

        //CHECK TABLE SECURITY SETTING
        $check = $this->db->get('security_setting')->row_object();
        if ($check && $check->status == 'CLOSE') {
            return [
                'status' => 400,
                'message' => 'Pengaturan sudah ditutup'
            ];
        }

        $this->db->empty_table('security_setting');
        $this->db->insert('security_setting', [
            'holiday' => ucwords($holiday),
            'gohome' => date('Y-m-d H:i:s', strtotime($gohome)),
            'comeback' => date('Y-m-d H:i:s', strtotime($comeback)),
            'status' => 'OPEN'
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Kesalahan server'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function setSetting($type)
    {
        $period = $this->dm->getperiod();
        $get = $this->db->get('security_setting')->row_object();
        if (!$get) {
            return [
                'status' => 400,
                'message' => 'Liburan dan tanggal belum diatur'
            ];
        }

        if ($type == 'OPEN') {
            $this->db->empty_table('security_setting');
            if ($this->db->affected_rows() <= 0) {
                return [
                    'status' => 400,
                    'message' => 'Kesalahan server'
                ];
            }

            return [
                'status' => 200,
                'message' => 'Sukses'
            ];
        }

        if ($type == 'CLOSE') {
            $holiday = $get->holiday;
            $check = $this->db->get_where('requirements', [
                'period' => $period, 'holiday' => $holiday
            ])->num_rows();
            if ($check > 0) {
                return [
                    'status' => 400,
                    'message' => 'Liburan ' . $holiday . ' pada periode ' . $period . ' sudah diatur'
                ];
            }

            $students = $this->db->get_where('students', ['status' => 'AKTIF'])->result_object();
            if ($students) {
                $data = [];
                foreach ($students as $student) {
                    $data[] = [
                        'student_id' => $student->id,
                        'period' => $period,
                        'holiday' => $holiday,
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => 'UNCHECKED'
                    ];
                }
                $this->db->insert_batch('requirements', $data);
                if ($this->db->affected_rows() <= 0) {
                    return [
                        'status' => 400,
                        'message' => 'Kesalahan server'
                    ];
                }

                $this->db->update('security_setting', ['status' => $type]);
                return [
                    'status' => 200,
                    'message' => 'Sukses'
                ];
            }
        }
    }
}
