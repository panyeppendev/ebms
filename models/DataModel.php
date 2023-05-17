<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataModel extends CI_Model
{
    public function rooms()
    {
        return $this->db->get('rooms')->result_object();
    }
    
    public function provinces($name)
    {
        $this->db->like('name', $name, 'both');
        $this->db->order_by('id', 'ASC');
        $this->db->limit(10);
        return $this->db->get('provinces')->result();
    }

    public function cities($id, $name)
    {
        $this->db->like('name', $name, 'both');
        $this->db->order_by('id', 'ASC');
        $this->db->limit(10);
        return $this->db->get_where('cities', ['province_id' => $id])->result();
    }

    public function districts($id, $name)
    {
        $this->db->like('name', $name, 'both');
        $this->db->order_by('id', 'ASC');
        $this->db->limit(10);
        return $this->db->get_where('districts', ['city_id' => $id])->result();
    }

    public function villages($id, $name)
    {
        $this->db->like('name', $name, 'both');
        $this->db->order_by('id', 'ASC');
        $this->db->limit(10);
        return $this->db->get_where('villages', ['district_id' => $id])->result();
    }

    public function getperiod()
    {
        $get = $this->db->order_by('id', 'DESC')->get('period')->row_object();
        if ($get) {
            return $get->name;
        } else {
            return '';
        }
    }

    public function periodDisplay()
    {
        $get = $this->db->order_by('id', 'DESC')->get('period')->row_object();
        if ($get) {
            return $get->name;
        } else {
            return 'Belum diatur';
        }
    }

    public function getHijri()
    {
        $now = new DateTime('now');
        $jam  = $now->format('H:m');
        $set = new DateTime('tomorrow');
        if ($jam > '18:00' and $jam < '23:59') {
            $set = new DateTime('tomorrow');
            $result = $set->format('Y-m-d');
        } else {
            $result = $now->format('Y-m-d');
        }

        $data = $this->db->get_where('calendar', ['masehi' => $result])->row_object();

        if ($data) {
            $hijri = $data->hijri;
        } else {
            $hijri = '0000-00-00';
        }

        return $hijri;
    }

    public function getHijriManual($date)
    {
        $data = $this->db->get_where('calendar', ['masehi' => $date])->row_object();

        if ($data) {
            $hijri = $data->hijri;
        } else {
            $hijri = '0000-00-00';
        }

        return $hijri;
    }
}
