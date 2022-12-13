<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingModel extends CI_Model
{
    private $_batchImport;
    private $_batchImportStudent;

    public function setperiod()
    {
        $period = $this->input->post('period', true);
        $periodReplace = str_replace('_', '', $period);
        $periodLength = strlen($periodReplace);
        if ($periodLength < 9) {
            return [
                'status' => 400,
                'message' => 'Tahun periode tidak valid'
            ];
        }

        $checkPeriode = $this->db->get_where('period', ['name' => $period])->num_rows();
        if ($checkPeriode > 0) {
            return [
                'status' => 400,
                'message' => 'Tahun periode sudah diatur sebelumnya'
            ];
        }

        $this->db->insert('period', ['name' => $period]);

        return $period;
    }

    public function setBatchImport($batchImport)
    {
        $this->_batchImport = $batchImport;
    }

    public function importData()
    {
        $data = $this->_batchImport;
        $this->db->insert_batch('calendar', $data);
    }

    public function setBatchImportStudent($batchImport)
    {
        $this->_batchImportStudent = $batchImport;
    }

    public function importDataStudent()
    {
        $data = $this->_batchImportStudent;
        $this->db->insert_batch('students', $data);
    }

    public function room()
    {
        $id = $this->input->post('id', true);
        $name = $this->input->post('name', true);
        $head = $this->input->post('head', true);
        $data = [
            'name' => ucwords($name),
            'head' => strtoupper($head)
        ];

        if ($id == 0) {
            $checkRoom = $this->db->get_where('rooms', ['name' => $name])->num_rows();
            if ($checkRoom > 0) {
                return [
                    'status' => 400,
                    'message' => 'Kamar sudah ada sebelumnya'
                ];
            } else {
                $this->db->insert('rooms', $data);
                if ($this->db->affected_rows() > 0) {
                    return [
                        'status' => 200,
                        'message' => 'Satu kamar berhasil ditambahkan'
                    ];
                } else {
                    return [
                        'status' => 400,
                        'message' => 'Terjadi kesalahan server'
                    ];
                }
            }
        } else {
            $this->db->where('id', $id)->update('rooms', $data);
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 200,
                    'message' => 'Satu kamar berhasil diedit'
                ];
            } else {
                return [
                    'status' => 400,
                    'message' => 'Terjadi kesalahan server'
                ];
            }
        }
    }


    public function loadroom()
    {
        return $this->db->get('rooms')->result_object();
    }

    public function getroomByid()
    {
        $id = $this->input->post('id', true);
        $data = $this->db->get_where('rooms', ['id' => $id])->row_object();
        if ($data) {
            return ['status' => 200, 'data' => $data];
        } else {
            return ['status' => 400, 'data' => []];
        }
    }

    public function deleteroom()
    {
        $id = $this->input->post('id', true);
        $checkRoom = $this->db->get_where('rooms', ['id' => $id])->num_rows();
        if ($checkRoom <= 0) {
            return [
                'status' => 400,
                'message' => 'Data tidak ditemukan'
            ];
        } else {
            $this->db->where('id', $id)->delete('rooms');
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 200,
                    'message' => 'Satu data berhasil dihapus'
                ];
            } else {
                return [
                    'status' => 400,
                    'message' => 'Terjadi kesalahan server'
                ];
            }
        }
    }
}
