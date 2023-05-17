<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('StudentModel', 'sm');
        $this->load->model('DataModel', 'dm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Santri Angkatan',
            'period' => $this->dm->getperiod(),
            'rooms' => $this->dm->rooms()
        ];
        $this->load->view('student/student', $data);
    }

    public function getdata()
    {
        $data = [
            'datas' => $this->sm->getData()[0],
            'amount' => $this->sm->getData()[1]
        ];
        $this->load->view('student/ajax-data', $data);
    }

    public function detaildata()
    {
        $id = $this->input->post('id', true);
        $data = [
            'data' => $this->sm->detailData($id),
            'card' => $this->sm->getCard($id)
        ];
        $this->load->view('student/ajax-detail', $data);
    }

    public function save()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nik', 'NIK', 'required|numeric|exact_length[16]');
        $this->form_validation->set_rules('kk', 'KK', 'required|numeric|exact_length[16]');
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('last_education', 'Pendidikan Akhir', 'required');
        $this->form_validation->set_rules('place_of_birth', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('date_of_birth', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('month_of_birth', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('year_of_birth', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('address', 'Alamat', 'required');
        $this->form_validation->set_rules('province', 'Provinsi', 'required');
        $this->form_validation->set_rules('city', 'Kab./Kota', 'required');
        $this->form_validation->set_rules('district', 'Kecamatan', 'required');
        $this->form_validation->set_rules('village', 'Desa', 'required');
        $this->form_validation->set_rules('father_nik', 'NIK Ayah', 'required|numeric|exact_length[16]');
        $this->form_validation->set_rules('father', 'Nama Ayah', 'required');
        $this->form_validation->set_rules('mother_nik', 'NIK Ibu', 'required|numeric|exact_length[16]');
        $this->form_validation->set_rules('mother', 'Nama Ibu', 'required');
        $this->form_validation->set_rules('phone', 'No. HP', 'required');
        // $this->form_validation->set_rules('status_of_domicile', 'Status Domisili', 'required');
        $this->form_validation->set_rules('domicile', 'Domisili', 'required');
        $this->form_validation->set_rules('class', 'Kelas Diniyah', 'required');
        $this->form_validation->set_rules('level', 'Tingkat Diniyah', 'required');
        $this->form_validation->set_rules('class_of_formal', 'Kelas Formal', 'required');
        $this->form_validation->set_rules('level_of_formal', 'Tingkat Formal', 'required');
        $this->form_validation->set_rules('date_of_entry', 'Tanggal Masuk (Masehi)', 'required');
        $this->form_validation->set_rules('month_of_entry', 'Tanggal Masuk (Masehi)', 'required');
        $this->form_validation->set_rules('year_of_entry', 'Tanggal Masuk (Masehi)', 'required');
        $this->form_validation->set_rules('date_of_entry_hijriah', 'Tanggal Masuk (Hijriah)', 'required');
        $this->form_validation->set_rules('month_of_entry_hijriah', 'Tanggal Masuk (Hijriah)', 'required');
        $this->form_validation->set_rules('year_of_entry_hijriah', 'Tanggal Masuk (Hijriah)', 'required');

        if ($this->form_validation->run() == FALSE) {
            $response = [
                'status' => 400,
                'errors' => [
                    'nik' => form_error('nik'),
                    'kk' => form_error('kk'),
                    'name' => form_error('name'),
                    'last_education' => form_error('last_education'),
                    'place_of_birth' => form_error('place_of_birth'),
                    'date_of_birth' => form_error('date_of_birth'),
                    'month_of_birth' => form_error('month_of_birth'),
                    'year_of_birth' => form_error('year_of_birth'),
                    'address' => form_error('address'),
                    'province' => form_error('province'),
                    'city' => form_error('city'),
                    'district' => form_error('district'),
                    'village' => form_error('village'),
                    'father_nik' => form_error('father_nik'),
                    'father' => form_error('father'),
                    'mother_nik' => form_error('mother_nik'),
                    'mother' => form_error('mother'),
                    'phone' => form_error('phone'),
                    // 'status_of_domicile' => form_error('status_of_domicile'),
                    'domicile' => form_error('domicile'),
                    'class' => form_error('class'),
                    'level' => form_error('level'),
                    'class_of_formal' => form_error('class_of_formal'),
                    'level_of_formal' => form_error('level_of_formal'),
                    'date_of_entry' => form_error('date_of_entry'),
                    'month_of_entry' => form_error('month_of_entry'),
                    'year_of_entry' => form_error('year_of_entry'),
                    'date_of_entry_hijriah' => form_error('date_of_entry_hijriah'),
                    'month_of_entry_hijriah' => form_error('month_of_entry_hijriah'),
                    'year_of_entry_hijriah' => form_error('year_of_entry_hijriah'),
                ]
            ];
        } else {
            $response = $this->sm->save();
        }

        echo json_encode($response);
    }

    public function cekid()
    {
        $result = $this->sm->cekid();

        echo json_encode($result);
    }

    public function print()
    {
        $domicile = $this->input->post('domicile_print', true);
        $data = [
            'title' => 'Print Out Data Santri',
            'data' => $this->sm->print($domicile),
            'domicile' => $domicile
        ];
        $this->load->view('print/student-print', $data);
    }

    public function getLink()
    {
        $card = $this->input->post('card', true);

        redirect('student/card/' . encrypt_url($card));
    }

    public function getlinkKTWS()
    {
        $id = $this->input->post('id', true);

        redirect('student/ktws/' . encrypt_url($id));
    }

    public function ktws($id)
    {
        $id = decrypt_url($id);

        $data = [
            'title' => 'Print Out Kartu Wali Santri',
            'barcode' => $this->barcode($id),
            'data' => $this->sm->getPrint($id)
        ];
        $this->load->view('print/ktws-print', $data);
    }

    public function card($card)
    {
        $card = decrypt_url($card);
        $id = $this->sm->getNisFromCard($card);

        $data = [
            'title' => 'Print Out Kartu Santri',
            'barcode' => $this->barcode($card),
            'data' => $this->sm->getPrint($id)
        ];
        $this->load->view('print/kts-print', $data);
    }

    public function barcode($id)
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        return $generator->getBarcode($id, $generator::TYPE_CODE_128, 16, 380);
    }

    public function behind()
    {
        $data = [
            'title' => 'Print Out Kartu Santri'
        ];
        $this->load->view('print/kts-behind', $data);
    }

    public function ktwsBehind()
    {
        $data = [
            'title' => 'Print Out Kartu Wali Santri'
        ];
        $this->load->view('print/ktws-behind', $data);
    }

    public function makeCard()
    {
        $result = $this->sm->makeCard();

        echo json_encode($result);
    }

    public function activeCard()
    {
        $result = $this->sm->activeCard();

        echo json_encode($result);
    }

    public function blockCard()
    {
        $result = $this->sm->blockCard();

        echo json_encode($result);
    }
}
