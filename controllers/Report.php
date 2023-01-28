<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('ReportModel', 'rm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Rekapitulasi',
            'step' => $this->rm->step()
        ];
        $this->load->view('report/report', $data);
    }

    public function loadPaymentReport()
    {
        $step = $this->input->post('step', true);
        $data = [
            'step' => $step,
            'pocket' => $this->rm->reportPocket($step),
            'besidesPocket' => $this->rm->besidesPocket($step)
        ];

        $this->load->view('report/ajax-payment-report', $data);
    }

    public function loadDisbursementReport()
    {
        $step = $this->input->post('step', true);
        $data = [
            'step' => $step,
            'data' => $this->rm->disbursement($step)
        ];

        $this->load->view('report/ajax-disbursement-report', $data);
    }

    public function exportPocket()
    {
        $step = $this->input->post('step', true);
        if ($step == 0) {
            $text = 'SEMUA TAHAP';
        } else {
            $text = 'TAHAP ' . $step;
        }

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
        $sheet->setCellValue('A1', 'REKAPITULASI PEMBAYARAN ' . $text); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', 'NO'); // Set kolom A3 dengan tulisan "NO"
        $sheet->setCellValue('B3', 'URAIAN'); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('C3', 'QTY'); // Set kolom C3 dengan tulisan "NAMA"
        $sheet->setCellValue('D3', 'NOMINAL'); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $sheet->setCellValue('E3', 'JUMLAH'); // Set kolom E3 dengan tulisan "ALAMAT"
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A3')->applyFromArray($style_col);
        $sheet->getStyle('B3')->applyFromArray($style_col);
        $sheet->getStyle('C3')->applyFromArray($style_col);
        $sheet->getStyle('D3')->applyFromArray($style_col);
        $sheet->getStyle('E3')->applyFromArray($style_col);
        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $pocket = $this->rm->reportPocket($step);
        $besides = $this->rm->besidesPocket($step);
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        $total = 0;
        foreach ($pocket as $p) { // Lakukan looping pada variabel siswa
            $qty = $p->qty;
            $amount = $p->amount;
            $jumlah = $amount * $qty;
            $total += $jumlah;
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, 'Uang Saku Paket ' . $p->package);
            $sheet->setCellValue('C' . $numrow, $qty);
            $sheet->setCellValue('D' . $numrow, $amount);
            $sheet->setCellValue('E' . $numrow, $jumlah);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }

        $sheet->setCellValue('A8', 'SUB TOTAL');
        $sheet->mergeCells('A8:D8');
        $sheet->setCellValue('E8', $total);
        $sheet->getStyle('A8')->applyFromArray($style_row);
        $sheet->getStyle('B8')->applyFromArray($style_row);
        $sheet->getStyle('C8')->applyFromArray($style_row);
        $sheet->getStyle('D8')->applyFromArray($style_row);
        $sheet->getStyle('E8')->applyFromArray($style_row);

        $totalBesides = 0;
        $noBesides = 5;
        $rowBesides = 9;
        foreach ($besides as $b) {
            $qty = $b->qty;
            $amount = $b->amount;
            $jumlah = $amount * $qty;
            $totalBesides += $jumlah;
            $sheet->setCellValue('A' . $rowBesides, $noBesides);
            $sheet->setCellValue('B' . $rowBesides, $b->name);
            $sheet->setCellValue('C' . $rowBesides, $qty);
            $sheet->setCellValue('D' . $rowBesides, $amount);
            $sheet->setCellValue('E' . $rowBesides, $jumlah);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A' . $rowBesides)->applyFromArray($style_row);
            $sheet->getStyle('B' . $rowBesides)->applyFromArray($style_row);
            $sheet->getStyle('C' . $rowBesides)->applyFromArray($style_row);
            $sheet->getStyle('D' . $rowBesides)->applyFromArray($style_row);
            $sheet->getStyle('E' . $rowBesides)->applyFromArray($style_row);

            $noBesides++; // Tambah 1 setiap kali looping
            $rowBesides++; // Tambah 1 setiap kali looping
        }

        $sheet->setCellValue('A13', 'SUB TOTAL');
        $sheet->mergeCells('A13:D13');
        $sheet->setCellValue('E13', $totalBesides);
        $sheet->setCellValue('A14', 'SUB TOTAL');
        $sheet->mergeCells('A14:D14');
        $sheet->setCellValue('E14', $total + $totalBesides);

        // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
        $sheet->getStyle('A13')->applyFromArray($style_row);
        $sheet->getStyle('B13')->applyFromArray($style_row);
        $sheet->getStyle('C13')->applyFromArray($style_row);
        $sheet->getStyle('D13')->applyFromArray($style_row);
        $sheet->getStyle('E13')->applyFromArray($style_row);
        $sheet->getStyle('A14')->applyFromArray($style_row);
        $sheet->getStyle('B14')->applyFromArray($style_row);
        $sheet->getStyle('C14')->applyFromArray($style_row);
        $sheet->getStyle('D14')->applyFromArray($style_row);
        $sheet->getStyle('E14')->applyFromArray($style_row);
        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Akumulasi Pembayaran");

        //SHEET 2
        $dataPayment = $this->rm->detailPayment($step);
        $secondSheet->setCellValue('A1', 'RINCIAN PEMBAYARAN ' . $text); // Set kolom A1 dengan tulisan "DATA SISWA"
        $secondSheet->mergeCells('A1:J1'); // Set Merge Cell pada kolom A1 sampai E1
        $secondSheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $secondSheet->setCellValue('A3', 'NO'); // Set kolom A3 dengan tulisan "NO"
        $secondSheet->setCellValue('B3', 'NAMA'); // Set kolom B3 dengan tulisan "NIS"
        $secondSheet->setCellValue('C3', 'DOMISILI'); // Set kolom C3 dengan tulisan "NAMA"
        $secondSheet->setCellValue('D3', 'ALAMAT'); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $secondSheet->setCellValue('E3', 'PAKET'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('F3', 'SAKU'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('G3', 'SARAPAN'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('H3', 'DPU'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('I3', 'ADMIN'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('J3', 'TRANSPORT'); // Set kolom E3 dengan tulisan "ALAMAT"
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $secondSheet->getStyle('A3')->applyFromArray($style_col);
        $secondSheet->getStyle('B3')->applyFromArray($style_col);
        $secondSheet->getStyle('C3')->applyFromArray($style_col);
        $secondSheet->getStyle('D3')->applyFromArray($style_col);
        $secondSheet->getStyle('E3')->applyFromArray($style_col);
        $secondSheet->getStyle('F3')->applyFromArray($style_col);
        $secondSheet->getStyle('G3')->applyFromArray($style_col);
        $secondSheet->getStyle('H3')->applyFromArray($style_col);
        $secondSheet->getStyle('I3')->applyFromArray($style_col);
        $secondSheet->getStyle('J3')->applyFromArray($style_col);

        $noPay = 1; // Untuk penomoran tabel, di awal set dengan 1
        $rowPay = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        if ($dataPayment != 0) {
            foreach ($dataPayment as $dp) { // Lakukan looping pada variabel siswa
                $address = $dp->village . ', ' . str_replace('Kabupaten', 'Kab.', $dp->city);

                $secondSheet->setCellValue('A' . $rowPay, $noPay);
                $secondSheet->setCellValue('B' . $rowPay, $dp->name);
                $secondSheet->setCellValue('C' . $rowPay, $dp->domicile);
                $secondSheet->setCellValue('D' . $rowPay, $address);
                $secondSheet->setCellValue('E' . $rowPay, $dp->package);
                $secondSheet->setCellValue('F' . $rowPay, $dp->pocket);
                $secondSheet->setCellValue('G' . $rowPay, $dp->breakfast);
                $secondSheet->setCellValue('H' . $rowPay, $dp->dpu);
                $secondSheet->setCellValue('I' . $rowPay, $dp->admin);
                $secondSheet->setCellValue('J' . $rowPay, $dp->transport);

                // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
                $secondSheet->getStyle('A' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('B' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('C' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('D' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('E' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('F' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('G' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('H' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('I' . $rowPay)->applyFromArray($style_row);
                $secondSheet->getStyle('J' . $rowPay)->applyFromArray($style_row);

                $noPay++; // Tambah 1 setiap kali looping
                $rowPay++; // Tambah 1 setiap kali looping
            }
        }
        $secondSheet->setTitle("Rincian Pemabayaran");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan-Pembayaran-Paket.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function exportDisbursement()
    {
        $step = $this->input->post('step', true);
        if ($step == 0) {
            $text = 'SEMUA TAHAP';
        } else {
            $text = 'TAHAP ' . $step;
        }

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
        $sheet->setCellValue('A1', 'REKAPITULASI PENCAIRAN ' . $text); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->mergeCells('A3:A4');
        $sheet->mergeCells('B3:B4');
        $sheet->mergeCells('L3:L4');
        $sheet->mergeCells('C3:E3');
        $sheet->mergeCells('F3:H3');
        $sheet->mergeCells('I3:K3');
        $sheet->setCellValue('A3', 'NO'); // Set kolom A3 dengan tulisan "NO"
        $sheet->setCellValue('B3', 'DOMISILI'); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('C3', 'UANG SAKU'); // Set kolom C3 dengan tulisan "NAMA"
        $sheet->setCellValue('C4', 'KREDIT'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('D4', 'DEBET'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('E4', 'SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('F3', 'SARAPAN'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('F4', 'KREDIT'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('G4', 'DEBET'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('H4', 'SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('I3', 'DPU'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('I4', 'KREDIT'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('J4', 'DEBET'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('K4', 'SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('L3', 'JUMLAH SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
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
        $sheet->getStyle('A4')->applyFromArray($style_col);
        $sheet->getStyle('B4')->applyFromArray($style_col);
        $sheet->getStyle('C4')->applyFromArray($style_col);
        $sheet->getStyle('D4')->applyFromArray($style_col);
        $sheet->getStyle('E4')->applyFromArray($style_col);
        $sheet->getStyle('F4')->applyFromArray($style_col);
        $sheet->getStyle('G4')->applyFromArray($style_col);
        $sheet->getStyle('H4')->applyFromArray($style_col);
        $sheet->getStyle('I4')->applyFromArray($style_col);
        $sheet->getStyle('J4')->applyFromArray($style_col);
        $sheet->getStyle('K4')->applyFromArray($style_col);
        $sheet->getStyle('L4')->applyFromArray($style_col);

        $data = $this->rm->disbursement($step);
        if ($data['status'] == 200) {
            $num = 1;
            $numrow = 5;
            foreach ($data['data'] as $d) {
                $kp = $d['kredit_pocket'];
                $dp = $d['debet_pocket'];
                $kb = $d['kredit_breakfast'];
                $db = $d['debet_breakfast'];
                $kd = $d['kredit_dpu'];
                $dd = $d['debet_dpu'];

                $sheet->setCellValue('A' . $numrow, $num);
                $sheet->setCellValue('B' . $numrow, $d['domicile']);
                $sheet->setCellValue('C' . $numrow, $kp);
                $sheet->setCellValue('D' . $numrow, $dp);
                $sheet->setCellValue('E' . $numrow, $kp - $dp);
                $sheet->setCellValue('F' . $numrow, $kb);
                $sheet->setCellValue('G' . $numrow, $db);
                $sheet->setCellValue('H' . $numrow, $kb - $db);
                $sheet->setCellValue('I' . $numrow, $kd);
                $sheet->setCellValue('J' . $numrow, $dd);
                $sheet->setCellValue('K' . $numrow, $kd - $dd);
                $sheet->setCellValue('L' . $numrow, ($kp - $dp) + ($kb - $db) + ($kd - $dd));

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

                $num++;
                $numrow++;
            }
        }

        $detailDisbursement = $this->rm->detailDisbursement($step);
        $secondSheet->setCellValue('A1', 'RINCIAN PENCAIRAN ' . $text); // Set kolom A1 dengan tulisan "DATA SISWA"
        $secondSheet->mergeCells('A1:R1'); // Set Merge Cell pada kolom A1 sampai E1
        $secondSheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        $secondSheet->setCellValue('A3', 'NO'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('B3', 'NAMA'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('C3', 'DOMISILI'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('D3', 'ALAMAT'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('E3', 'PAKET'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('F3', 'UANG SAKU'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('G3', 'TUNAI'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('H3', 'KANTIN'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('I3', 'KOPERASI'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('J3', 'PERPUS'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('K3', 'SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('L3', 'SARAPAN'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('M3', 'DEBET'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('N3', 'SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('O3', 'DPU'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('P3', 'PAGI'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('Q3', 'SORE'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->setCellValue('R3', 'SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
        $secondSheet->getStyle('A3')->applyFromArray($style_col);
        $secondSheet->getStyle('B3')->applyFromArray($style_col);
        $secondSheet->getStyle('C3')->applyFromArray($style_col);
        $secondSheet->getStyle('D3')->applyFromArray($style_col);
        $secondSheet->getStyle('E3')->applyFromArray($style_col);
        $secondSheet->getStyle('F3')->applyFromArray($style_col);
        $secondSheet->getStyle('G3')->applyFromArray($style_col);
        $secondSheet->getStyle('H3')->applyFromArray($style_col);
        $secondSheet->getStyle('I3')->applyFromArray($style_col);
        $secondSheet->getStyle('J3')->applyFromArray($style_col);
        $secondSheet->getStyle('K3')->applyFromArray($style_col);
        $secondSheet->getStyle('L3')->applyFromArray($style_col);
        $secondSheet->getStyle('M3')->applyFromArray($style_col);
        $secondSheet->getStyle('N3')->applyFromArray($style_col);
        $secondSheet->getStyle('O3')->applyFromArray($style_col);
        $secondSheet->getStyle('P3')->applyFromArray($style_col);
        $secondSheet->getStyle('Q3')->applyFromArray($style_col);
        $secondSheet->getStyle('R3')->applyFromArray($style_col);
        if ($detailDisbursement) {
            $numDis = 1;
            $rowDis = 4;
            foreach ($detailDisbursement as $dd) {
                $package = $dd->package;
                if ($package == 'A' || $package == 'B') {
                    $pocket = 150000;
                } else {
                    $pocket = 300000;
                }

                if ($package == 'B' || $package == 'D') {
                    $breakfast = 120000;
                } else {
                    $breakfast = 0;
                }

                $cash = $dd->pocket_cash;
                $canteen = $dd->pocket_canteen;
                $store = $dd->pocket_store;
                $library = $dd->pocket_library;
                $pocketResidual = $pocket - $cash - $canteen - $store - $library;


                $secondSheet->setCellValue('A' . $rowDis, $numDis);
                $secondSheet->setCellValue('B' . $rowDis, $dd->name);
                $secondSheet->setCellValue('C' . $rowDis, $dd->domicile);
                $secondSheet->setCellValue('D' . $rowDis, $dd->village . ', ' . str_replace('Kabupaten', 'Kab.', $dd->city));
                $secondSheet->setCellValue('E' . $rowDis, $package);
                $secondSheet->setCellValue('F' . $rowDis, $pocket);
                $secondSheet->setCellValue('G' . $rowDis, $cash);
                $secondSheet->setCellValue('H' . $rowDis, $canteen);
                $secondSheet->setCellValue('I' . $rowDis, $store);
                $secondSheet->setCellValue('J' . $rowDis, $library);
                $secondSheet->setCellValue('K' . $rowDis, $pocketResidual);
                $secondSheet->setCellValue('L' . $rowDis, $breakfast);
                $secondSheet->setCellValue('M' . $rowDis, $dd->breakfast);
                $secondSheet->setCellValue('N' . $rowDis, $breakfast - $dd->breakfast);
                $secondSheet->setCellValue('O' . $rowDis, 200000);
                $secondSheet->setCellValue('P' . $rowDis, $dd->morning);
                $secondSheet->setCellValue('Q' . $rowDis, $dd->afternoon);
                $secondSheet->setCellValue('R' . $rowDis, 200000 - $dd->morning - $dd->afternoon);

                $secondSheet->getStyle('A' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('B' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('C' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('D' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('E' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('F' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('G' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('H' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('I' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('J' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('K' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('L' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('M' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('N' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('O' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('P' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('Q' . $rowDis)->applyFromArray($style_row);
                $secondSheet->getStyle('R' . $rowDis)->applyFromArray($style_row);

                $rowDis++;
                $numDis++;
            }
        }

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Akumulasi Pencairan");

        $secondSheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $secondSheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $secondSheet->setTitle("Rincian Pencairan");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan-Pencairan-Paket.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function exportDeposit()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(0);
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
        $sheet->setCellValue('A1', 'REKAPITULASI TABUNGAN'); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:J1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', 'NO'); // Set kolom A3 dengan tulisan "NO"
        $sheet->setCellValue('B3', 'NAMA'); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('C3', 'DOMISILI'); // Set kolom C3 dengan tulisan "NAMA"
        $sheet->setCellValue('D3', 'ALAMAT'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('E3', 'TABUNGAN'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('F3', 'TUNAI'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('G3', 'KANTIN'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('H3', 'KOPERASI'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('I3', 'PERPUS'); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('J3', 'SISA'); // Set kolom E3 dengan tulisan "ALAMAT"
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

        $data = $this->rm->deposit();
        if ($data) {
            $num = 1;
            $numrow = 5;
            foreach ($data as $d) {
                $deposit = $d['deposit'];
                $cash = $d['cash'];
                $canteen = $d['canteen'];
                $store = $d['store'];
                $library = $d['library'];
                $id = $d['id'];
                $residual = $deposit - ($cash + $canteen + $store + $library);

                $sheet->setCellValue('A' . $numrow, $num);
                $sheet->setCellValue('B' . $numrow, $d['name']);
                $sheet->setCellValue('C' . $numrow, $d['domicile']);
                $sheet->setCellValue('D' . $numrow, $d['village'].', '.$d['city']);
                $sheet->setCellValue('E' . $numrow, $deposit);
                $sheet->setCellValue('F' . $numrow, $cash);
                $sheet->setCellValue('G' . $numrow, $canteen);
                $sheet->setCellValue('H' . $numrow, $store);
                $sheet->setCellValue('I' . $numrow, $library);
                $sheet->setCellValue('J' . $numrow, $residual);

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

                $num++;
                $numrow++;
            }
        }

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Rekapitulasi Tabungan");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan-Tabungan.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function log()
    {
        $type = $this->input->post('type', true);
        $date = $this->input->post('date', true);

        $text = [
            'CASH' => 'TUNAI',
            'CANTEEN' => 'KANTIN',
            'STORE' => 'KOPERASI',
            'LIBRARY' => 'PERPUS'
        ];
        $data = [
            'title' => 'Print Out Log Transaksi',
            'type' => $text[$type],
            'date' => dateIDFormat($date),
            'data' => $this->rm->log($type, $date)
        ];
        $this->load->view('print/log-transaction', $data);
    }

}
