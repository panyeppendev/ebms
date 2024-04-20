<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('SettingModel', 'sm');
        $this->load->helper('download');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Setting Awal Sistem',
            'period' => $this->dm->getperiod()
        ];
        $this->load->view('setting/setting', $data);
    }

    public function setperiod()
    {
        $result = $this->sm->setperiod();

        echo json_encode($result);
    }

    public function import()
    {
        $file_mimes = array(
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );


        $arr_file = explode('.', $_FILES['file_calendar']['name']);
        $extension = end($arr_file);

        if (($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') && in_array($_FILES['file_calendar']['type'], $file_mimes)) {

            $extension = pathinfo($_FILES['file_calendar']['name'], PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ($extension == 'xlsx') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            // file path
            $spreadsheet = $reader->load($_FILES['file_calendar']['tmp_name']);
            $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // array Count
            $arrayCount = count($allDataInSheet);
            $flag = 0;
            $createArray = array('hijri', 'masehi');
            $makeArray = array('hijri' => 'hijri', 'masehi' => 'masehi');
            $SheetDataKey = array();
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
                    }
                }
            }
            $dataDiff = array_diff_key($makeArray, $SheetDataKey);
            if (empty($dataDiff)) {
                $flag = 1;
            }
            // match excel sheet column
            if ($flag == 1) {
                for ($i = 2; $i <= $arrayCount; $i++) {
                    $hijri = $SheetDataKey['hijri'];
                    $masehi = $SheetDataKey['masehi'];

                    $hijri = filter_var(trim($allDataInSheet[$i][$hijri]), FILTER_SANITIZE_STRING);
                    $masehi = filter_var(trim($allDataInSheet[$i][$masehi]), FILTER_SANITIZE_STRING);
                    $fetchData[] = array('hijri' => $hijri, 'masehi' => $masehi);
                }

                $this->sm->setBatchImport($fetchData);
                $this->sm->importData();

                $this->session->set_flashdata('import-calendar', 1);
            } else {
                $this->session->set_flashdata('import-calendar', 2);
            }
        } else {
            $this->session->set_flashdata('import-calendar', 3);
        }

        redirect('setting');
    }

    public function importstudent()
    {
        $file_mimes = array(
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );


        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);

        if (($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') && in_array($_FILES['file']['type'], $file_mimes)) {

            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if ($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ($extension == 'xlsx') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            // file path
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // array Count
            $arrayCount = count($allDataInSheet);
            $flag = 0;
            $createArray = array(
                'id', 'year_of_entry', 'date_of_entry', 'date_of_entry_hijriah',
                'period', 'nik', 'kk', 'name', 'place_of_birth', 'date_of_birth',
                'last_education', 'address', 'village', 'district', 'city',
                'province', 'postal_code', 'father_nik', 'father', 'mother_nik',
                'mother', 'phone', 'status_of_domicile', 'domicile', 'class', 'level',
                'class_of_formal', 'level_of_formal'
            );
            $makeArray = array(
                'id' => 'id', 'year_of_entry' => 'year_of_entry',
                'date_of_entry' => 'date_of_entry',
                'date_of_entry_hijriah' => 'date_of_entry_hijriah',
                'period' => 'period', 'nik' => 'nik', 'kk' => 'kk',
                'name' => 'name', 'place_of_birth' => 'place_of_birth',
                'date_of_birth' => 'date_of_birth', 'last_education' => 'last_education',
                'address' => 'address', 'village' => 'village', 'district' => 'district',
                'city' => 'city', 'province' => 'province', 'postal_code' => 'postal_code',
                'father_nik' => 'father_nik', 'father' => 'father', 'mother_nik' => 'mother_nik',
                'mother' => 'mother', 'phone' => 'phone', 'status_of_domicile' => 'status_of_domicile',
                'domicile' => 'domicile', 'class' => 'class', 'level' => 'level',
                'class_of_formal' => 'class_of_formal', 'level_of_formal' => 'level_of_formal'
            );
            $SheetDataKey = array();
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
                    }
                }
            }
            $dataDiff = array_diff_key($makeArray, $SheetDataKey);
            if (empty($dataDiff)) {
                $flag = 1;
            }
            // match excel sheet column
            if ($flag == 1) {
                for ($i = 2; $i <= $arrayCount; $i++) {

                    $id = filter_var(trim($allDataInSheet[$i][$SheetDataKey['id']]), FILTER_SANITIZE_STRING);
                    $year_of_entry = filter_var(trim($allDataInSheet[$i][$SheetDataKey['year_of_entry']]), FILTER_SANITIZE_STRING);
                    $date_of_entry = filter_var(trim($allDataInSheet[$i][$SheetDataKey['date_of_entry']]), FILTER_SANITIZE_STRING);
                    $date_of_entry_hijriah = filter_var(trim($allDataInSheet[$i][$SheetDataKey['date_of_entry_hijriah']]), FILTER_SANITIZE_STRING);
                    $period = filter_var(trim($allDataInSheet[$i][$SheetDataKey['period']]), FILTER_SANITIZE_STRING);
                    $nik = filter_var(trim($allDataInSheet[$i][$SheetDataKey['nik']]), FILTER_SANITIZE_STRING);
                    $kk = filter_var(trim($allDataInSheet[$i][$SheetDataKey['kk']]), FILTER_SANITIZE_STRING);
                    $name = filter_var(trim($allDataInSheet[$i][$SheetDataKey['name']]), FILTER_SANITIZE_STRING);
                    $place_of_birth = filter_var(trim($allDataInSheet[$i][$SheetDataKey['place_of_birth']]), FILTER_SANITIZE_STRING);
                    $date_of_birth = filter_var(trim($allDataInSheet[$i][$SheetDataKey['date_of_birth']]), FILTER_SANITIZE_STRING);
                    $last_education = filter_var(trim($allDataInSheet[$i][$SheetDataKey['last_education']]), FILTER_SANITIZE_STRING);
                    $address = filter_var(trim($allDataInSheet[$i][$SheetDataKey['address']]), FILTER_SANITIZE_STRING);
                    $village = filter_var(trim($allDataInSheet[$i][$SheetDataKey['village']]), FILTER_SANITIZE_STRING);
                    $district = filter_var(trim($allDataInSheet[$i][$SheetDataKey['district']]), FILTER_SANITIZE_STRING);
                    $city = filter_var(trim($allDataInSheet[$i][$SheetDataKey['city']]), FILTER_SANITIZE_STRING);
                    $province = filter_var(trim($allDataInSheet[$i][$SheetDataKey['province']]), FILTER_SANITIZE_STRING);
                    $postal_code = filter_var(trim($allDataInSheet[$i][$SheetDataKey['postal_code']]), FILTER_SANITIZE_STRING);
                    $father_nik = filter_var(trim($allDataInSheet[$i][$SheetDataKey['father_nik']]), FILTER_SANITIZE_STRING);
                    $father = filter_var(trim($allDataInSheet[$i][$SheetDataKey['father']]), FILTER_SANITIZE_STRING);
                    $mother_nik = filter_var(trim($allDataInSheet[$i][$SheetDataKey['mother_nik']]), FILTER_SANITIZE_STRING);
                    $mother = filter_var(trim($allDataInSheet[$i][$SheetDataKey['mother']]), FILTER_SANITIZE_STRING);
                    $phone = filter_var(trim($allDataInSheet[$i][$SheetDataKey['phone']]), FILTER_SANITIZE_STRING);
                    $status_of_domicile = filter_var(trim($allDataInSheet[$i][$SheetDataKey['status_of_domicile']]), FILTER_SANITIZE_STRING);
                    $domicile = filter_var(trim($allDataInSheet[$i][$SheetDataKey['domicile']]), FILTER_SANITIZE_STRING);
                    $class = filter_var(trim($allDataInSheet[$i][$SheetDataKey['class']]), FILTER_SANITIZE_STRING);
                    $level = filter_var(trim($allDataInSheet[$i][$SheetDataKey['level']]), FILTER_SANITIZE_STRING);
                    $class_of_formal = filter_var(trim($allDataInSheet[$i][$SheetDataKey['class_of_formal']]), FILTER_SANITIZE_STRING);
                    $level_of_formal = filter_var(trim($allDataInSheet[$i][$SheetDataKey['level_of_formal']]), FILTER_SANITIZE_STRING);
                    $fetchData[] = array(
                        'id' => $id,
                        'year_of_entry' => $year_of_entry,
                        'date_of_entry' => $date_of_entry,
                        'date_of_entry_hijriah' => $date_of_entry_hijriah,
                        'period' => $period,
                        'nik' => $nik,
                        'kk' => $kk,
                        'name' => $name,
                        'place_of_birth' => $place_of_birth,
                        'date_of_birth' => $date_of_birth,
                        'last_education' => $last_education,
                        'address' => $address,
                        'village' => $village,
                        'district' => $district,
                        'city' => $city,
                        'province' => $province,
                        'postal_code' => $postal_code,
                        'father_nik' => $father_nik,
                        'father' => $father,
                        'mother_nik' => $mother_nik,
                        'mother' => $mother,
                        'phone' => $phone,
                        'status_of_domicile' => $status_of_domicile,
                        'domicile' => $domicile,
                        'class' => $class,
                        'level' => 'I\'dadiyah',
                        'class_of_formal' => $class_of_formal,
                        'level_of_formal' => $level_of_formal,
                        'status' => 'AKTIF',
                        'user_id' => $this->session->userdata('user_id')
                    );
                }
                $this->sm->setBatchImportStudent($fetchData);
                $this->sm->importDataStudent();

                $this->session->set_flashdata('import-student', 1);
            } else {
                $this->session->set_flashdata('import-student', 2);
            }
        } else {
            $this->session->set_flashdata('import-student', 3);
        }

        redirect('setting');
    }

    public function sample($type)
    {
        if ($type == 1) {
            $file = 'assets/files/sample-student.xlsx';
        } else {
            $file = 'assets/files/sample-calendar.xlsx';
        }

        force_download($file, NULL);
    }

    public function room()
    {
        $result = $this->sm->room();

        echo json_encode($result);
    }

    public function loadroom()
    {
        $data = ['rooms' => $this->sm->loadroom()];
        $this->load->view('setting/ajax-room', $data);
    }

    public function getroomByid()
    {
        $result = $this->sm->getroomByid();

        echo json_encode($result);
    }

    public function deleteroom()
    {
        $result = $this->sm->deleteroom();

        echo json_encode($result);
    }

	public function resetAll()
	{
		$this->db->empty_table('daily_pocket_limit');
		$this->db->empty_table('deposit_credit');
		$this->db->empty_table('deposit_debit');
		$this->db->empty_table('disbursements');
		$this->db->empty_table('distribution_daily');
		$this->db->empty_table('distributions');
		$this->db->empty_table('expenditures');
		$this->db->empty_table('package_transaction');
		$this->db->empty_table('purchase_detail');
		$this->db->empty_table('purchase_temp');
		$this->db->empty_table('purchases');
		$this->db->empty_table('reserved_pocket');
		$this->db->empty_table('reserved_pocket_daily');
		$this->db->empty_table('transactions');
		$this->db->empty_table('set_daily');
		$this->db->empty_table('set_daily_log');
		$this->db->empty_table('set_transaction_daily');

		$result = [
			'status' => TRUE,
			'message' => 'Berhasil direset'
		];

		echo json_encode($result);
	}
}
