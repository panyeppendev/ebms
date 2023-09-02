<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

	public function destroy()
	{
		$result = $this->sm->destroy();

		echo json_encode($result);
	}

    // public function renameFather()
    // {
    //     $student = $this->db->get('students')->result_object();
    //     foreach ($student as $s) {
    //         $avatarPath = FCPATH . 'assets/fathers/' . $s->id . '.jpg';
    //         $id = $s->id;
    //         if (file_exists($avatarPath)) {
	// 			rename($avatarPath, FCPATH.'assets/fathers/'.$s->new_id.'.jpg');
	// 		}
    //     }
    // }

	public function exportData()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet(0);
		$secondSheet = $spreadsheet->createSheet();
		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = [
			'font' => ['bold' => true], // Set font nya jadi bold
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			]
		];
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			]
		];

		$sheet->setCellValue('A1', 'DATA SANTRI PONDOK PESANTREN MIFTAHUL ULUM PANYEPPEN'); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:Z1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

		$sheet->setCellValue('A3', 'NO'); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', 'NIS'); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', 'PERIODE'); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('D3', 'NIK'); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('E3', 'KK'); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('F3', 'NAMA'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('G3', 'TEMPAT LAHIR'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('H3', 'TANGGAL LAHIR'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('I3', 'ALAMAT'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('J3', 'DESA'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('K3', 'KECAMATAN'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('L3', 'KABUPATEN/KOTA'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('M3', 'PROVINSI'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('N3', 'KODE POS'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('O3', 'NIK AYAH'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('P3', 'NAMA AYAH'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('Q3', 'NIK IBU'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('R3', 'NAMA IBU'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('S3', 'TELEPON'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('T3', 'DOMISILI'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('U3', 'KELAS'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('V3', 'TINGKAT'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('W3', 'KELAS FORMAL'); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('X3', 'TINGKAT FORMAL'); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		$sheet->getStyle('G3')->applyFromArray($style_col);
		$sheet->getStyle('H3')->applyFromArray($style_col);
		$sheet->getStyle('I3')->applyFromArray($style_col);
		$sheet->getStyle('J3')->applyFromArray($style_col);
		$sheet->getStyle('K3')->applyFromArray($style_col);
		$sheet->getStyle('L3')->applyFromArray($style_col);
		$sheet->getStyle('M3')->applyFromArray($style_col);
		$sheet->getStyle('N3')->applyFromArray($style_col);
		$sheet->getStyle('O3')->applyFromArray($style_col);
		$sheet->getStyle('P3')->applyFromArray($style_col);
		$sheet->getStyle('Q3')->applyFromArray($style_col);
		$sheet->getStyle('R3')->applyFromArray($style_col);
		$sheet->getStyle('S3')->applyFromArray($style_col);
		$sheet->getStyle('T3')->applyFromArray($style_col);
		$sheet->getStyle('U3')->applyFromArray($style_col);
		$sheet->getStyle('V3')->applyFromArray($style_col);
		$sheet->getStyle('W3')->applyFromArray($style_col);
		$sheet->getStyle('X3')->applyFromArray($style_col);

		$data = $this->sm->exportStudent();
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		$total = 0;
		foreach ($data as $d) { // Lakukan looping pada variabel siswa

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $d->id);
			$sheet->setCellValue('C' . $numrow, $d->period);
			$sheet->setCellValue('D' . $numrow, "'".$d->nik);
			$sheet->setCellValue('E' . $numrow, "'".$d->kk);
			$sheet->setCellValue('F' . $numrow, $d->name);
			$sheet->setCellValue('G' . $numrow, $d->place_of_birth);
			$sheet->setCellValue('H' . $numrow, $d->date_of_birth);
			$sheet->setCellValue('I' . $numrow, $d->address);
			$sheet->setCellValue('J' . $numrow, $d->village);
			$sheet->setCellValue('K' . $numrow, $d->district);
			$sheet->setCellValue('L' . $numrow, $d->city);
			$sheet->setCellValue('M' . $numrow, $d->province);
			$sheet->setCellValue('N' . $numrow, $d->postal_code);
			$sheet->setCellValue('O' . $numrow, "'".$d->father_nik);
			$sheet->setCellValue('P' . $numrow, $d->father);
			$sheet->setCellValue('Q' . $numrow, "'".$d->mother_nik);
			$sheet->setCellValue('R' . $numrow, $d->mother);
			$sheet->setCellValue('S' . $numrow, "'".$d->phone);
			$sheet->setCellValue('T' . $numrow, $d->domicile);
			$sheet->setCellValue('U' . $numrow, $d->class);
			$sheet->setCellValue('V' . $numrow, $d->level);
			$sheet->setCellValue('W' . $numrow, $d->class_of_formal);
			$sheet->setCellValue('X' . $numrow, $d->level_of_formal);

			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('M' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('P' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('Q' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('R' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('S' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('T' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('U' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('V' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('W' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('X' . $numrow)->applyFromArray($style_row);

			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="DATA-SANTRI.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
