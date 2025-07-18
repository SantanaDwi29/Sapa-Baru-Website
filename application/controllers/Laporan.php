<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'PendatangModel', 'DaftarModel', 'LogModel']);
        $this->load->helper(['url', 'profile']);
        $this->validasi->validasiakun();
    }

    public function index()
    {
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $data = get_profile_data();
        $data['konten'] = $this->load->view('laporan', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function pendatang($format)
    {
        $jenisAkun = $this->session->userdata('JenisAkun');
        $idUser = $this->session->userdata('id_user');

        $data['pendatang'] = [];
        if ($jenisAkun == 'Admin') {
            $data['pendatang'] = $this->PendatangModel->get_laporan_pendatang_aktif();
        } else if ($jenisAkun == 'Kepala Lingkungan') {
            $data['pendatang'] = $this->PendatangModel->get_laporan_pendatang_aktif($idUser);
        }

        $data['judul'] = "Laporan Data Pendatang Aktif";

        if (strtolower($format) == 'pdf') {
            $this->_generate_pdf_pendatang($data);
        } else if (strtolower($format) == 'excel') {
            $this->_generate_excel_pendatang($data);
        } else {
            show_404();
        }
    }

     public function penanggung_jawab($format)
    {
        if ($this->session->userdata('JenisAkun') != 'Admin') {
            show_error('Hanya Admin yang dapat mengakses laporan ini.', 403, 'Akses Ditolak');
        }

        $data['penanggung_jawab'] = $this->DaftarModel->get_all_pj();
        $data['judul'] = "Laporan Data Penanggung Jawab";

        if (strtolower($format) == 'pdf') {
            $this->_generate_pdf_pj($data);
        } else if (strtolower($format) == 'excel') {
            $this->_generate_excel_pj($data);
        } else {
            show_404();
        }
    }

     public function arsip($format)
    {
        $jenisAkun = $this->session->userdata('JenisAkun');
        $idUser = $this->session->userdata('id_user');

        $data['pendatang'] = [];
        if ($jenisAkun == 'Admin') {
            $data['pendatang'] = $this->PendatangModel->get_laporan_pendatang_arsip();
        } else if ($jenisAkun == 'Kepala Lingkungan') {
            $data['pendatang'] = $this->PendatangModel->get_laporan_pendatang_arsip($idUser);
        }

        $data['judul'] = "Laporan Arsip Data Pendatang";

        if (strtolower($format) == 'pdf') {
            $this->_generate_pdf_pendatang($data);
        } else if (strtolower($format) == 'excel') {
            $this->_generate_excel_pendatang($data);
        } else {
            show_404();
        }
    }

       public function view_log_surat()
    {
        $data = get_profile_data();
        $data['logs'] = $this->LogModel->get_riwayat_pengajuan();
        $data['judul_halaman'] = "Riwayat Pengajuan Surat";
        $data['konten'] = $this->load->view('laporan/view_log_surat', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function log($format)
    {
        if ($this->session->userdata('JenisAkun') != 'Admin') {
            show_error('Hanya Admin yang dapat mengakses fitur ini.', 403, 'Akses Ditolak');
        }
        
        $data['logs'] = $this->LogModel->get_riwayat_pengajuan();
        $data['judul'] = "Laporan Riwayat Pengajuan Surat";

        if (strtolower($format) == 'pdf') {
            $this->_generate_pdf_log($data);
        } else if (strtolower($format) == 'excel') {
            $this->_generate_excel_log($data);
        } else {
            show_404();
        }
    }

   
    public function rekap_lingkungan()
    {
        if ($this->session->userdata('JenisAkun') != 'Admin') {
            show_error('Hanya Admin yang dapat mengakses laporan ini.', 403, 'Akses Ditolak');
        }

        $data = get_profile_data();
        $data['rekap'] = $this->PendatangModel->get_rekap_by_lingkungan();
        $data['judul_halaman'] = "Rekapitulasi Pendatang per Lingkungan";
        $data['konten'] = $this->load->view('laporan/view_rekap_lingkungan', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function export_rekap_lingkungan($format)
    {
        if ($this->session->userdata('JenisAkun') != 'Admin') {
            show_error('Hanya Admin yang dapat mengakses fitur ini.', 403, 'Akses Ditolak');
        }
        
        $data['rekap'] = $this->PendatangModel->get_rekap_by_lingkungan();
        $data['judul'] = "Laporan Rekapitulasi Pendatang per Lingkungan";

        if (strtolower($format) == 'pdf') {
            $this->_generate_pdf_rekap_lingkungan($data);
        } else if (strtolower($format) == 'excel') {
            $this->_generate_excel_rekap_lingkungan($data);
        } else {
            show_404();
        }
    }


    private function _generate_pdf_pendatang($data)
    {
        require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $dompdf = new Dompdf($options);
        
        $html = $this->load->view('laporan/pdf_template_pendatang', $data, TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("laporan-pendatang-".date('Ymd').".pdf", ["Attachment" => false]);
        exit();
    }
    
    private function _generate_pdf_pj($data)
    {
        require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $dompdf = new Dompdf($options);
        
        $html = $this->load->view('laporan/pdf_template_pj', $data, TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan-penanggung-jawab-".date('Ymd').".pdf", ["Attachment" => false]);
        exit();
    }

    private function _generate_pdf_log($data)
    {
        require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $dompdf = new Dompdf($options);
        
        $html = $this->load->view('laporan/pdf_template_log', $data, TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Laporan-Log-Surat-".date('Ymd').".pdf", ["Attachment" => false]);
        exit();
    }
    
    private function _generate_pdf_rekap_lingkungan($data)
    {
        require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $dompdf = new Dompdf($options);

        $html = $this->load->view('laporan/pdf_template_rekap_lingkungan', $data, TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan-rekap-lingkungan-".date('Ymd').".pdf", ["Attachment" => 0]);
        exit();
    }

    private function _generate_excel_pendatang($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $styleJudul = ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]];
        $styleHeader = ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD3D3D3']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER], 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $styleData = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];

        $sheet->mergeCells('A1:G1')->setCellValue('A1', strtoupper($data['judul']))->getStyle('A1')->applyFromArray($styleJudul);
        $sheet->getRowDimension('1')->setRowHeight(20);
        $sheet->mergeCells('A2:G2')->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i:s'))->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerRow = 4;
        $sheet->setCellValue('A'.$headerRow, 'No');
        $sheet->setCellValue('B'.$headerRow, 'Nama Lengkap');
        $sheet->setCellValue('C'.$headerRow, 'NIK');
        $sheet->setCellValue('D'.$headerRow, 'Jenis Kelamin');
        $sheet->setCellValue('E'.$headerRow, 'Alamat Tujuan');
        $sheet->setCellValue('F'.$headerRow, 'Tanggal Masuk');
        $sheet->setCellValue('G'.$headerRow, 'Kepala Lingkungan');
        $sheet->getStyle('A'.$headerRow.':G'.$headerRow)->applyFromArray($styleHeader);

        $row = $headerRow + 1;
        $no = 1;
        foreach ($data['pendatang'] as $p) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $p->NamaLengkap);
            $sheet->setCellValueExplicit('C'.$row, $p->NIK, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D'.$row, $p->JenisKelamin);
            $sheet->setCellValue('E'.$row, $p->TempatTujuan);
            $sheet->setCellValue('F'.$row, $p->TanggalMasuk);
            $sheet->setCellValue('G'.$row, $p->NamaKaling);
            $row++;
        }

        $lastRow = $row - 1;
        if ($lastRow >= ($headerRow + 1)) {
            $sheet->getStyle('A'.($headerRow + 1).':G'.$lastRow)->applyFromArray($styleData);
        }
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
       
        $writer = new Xlsx($spreadsheet);
        $filename = "laporan-pendatang-".date('Ymd').".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    private function _generate_excel_pj($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $styleJudul = ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]];
        $styleHeader = ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD3D3D3']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER], 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $styleData = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];

        $sheet->mergeCells('A1:E1')->setCellValue('A1', strtoupper($data['judul']))->getStyle('A1')->applyFromArray($styleJudul);
        $sheet->getRowDimension('1')->setRowHeight(20);
        $sheet->mergeCells('A2:E2')->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i:s'))->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerRow = 4;
        $sheet->setCellValue('A'.$headerRow, 'No');
        $sheet->setCellValue('B'.$headerRow, 'Nama Lengkap');
        $sheet->setCellValue('C'.$headerRow, 'NIK');
        $sheet->setCellValue('D'.$headerRow, 'No. Telepon');
        $sheet->setCellValue('E'.$headerRow, 'Email');
        $sheet->getStyle('A'.$headerRow.':E'.$headerRow)->applyFromArray($styleHeader);

        $row = $headerRow + 1;
        $no = 1;
        foreach ($data['penanggung_jawab'] as $pj) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $pj->NamaPJ);
            $sheet->setCellValueExplicit('C'.$row, $pj->NIK, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D'.$row, $pj->Telp);
            $sheet->setCellValue('E'.$row, $pj->Email);
            $row++;
        }

        $lastRow = $row - 1;
        if ($lastRow >= ($headerRow + 1)) {
            $sheet->getStyle('A'.($headerRow + 1).':E'.$lastRow)->applyFromArray($styleData);
        }
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $filename = "laporan-penanggung-jawab-".date('Ymd').".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    private function _generate_excel_log($data)
    {
        $spreadsheet = new Spreadsheet();
        
        $styleJudul = ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]];
        $styleHeader = ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD3D3D3']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER], 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $styleData = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $styleDitolak = ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC7CE']], 'font' => ['color' => ['argb' => 'FF9C0006']], 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $styleDiverifikasi = ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFC6EFCE']], 'font' => ['color' => ['argb' => 'FF006100']], 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $stylePending = ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFEB9C']], 'font' => ['color' => ['argb' => 'FF9C6500']], 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];

        $logsByStatus = ['diverifikasi' => [], 'ditolak' => [], 'pending' => []];
        foreach ($data['logs'] as $log) {
            $status = strtolower($log->StatusPengajuan);
            if ($status == 'diverifikasi' || $status == 'terverifikasi' || $status == 'disetujui') {
                $logsByStatus['diverifikasi'][] = $log;
            } else if ($status == 'ditolak') {
                $logsByStatus['ditolak'][] = $log;
            } else if ($status == 'pending' || $status == 'menunggu verifikasi' || $status == 'proses') {
                $logsByStatus['pending'][] = $log;
            }
        }

        $createHeader = function(Worksheet $sheet, $title) use ($styleJudul, $styleHeader) {
            $sheet->mergeCells('A1:G1')->setCellValue('A1', strtoupper($title))->getStyle('A1')->applyFromArray($styleJudul);
            $sheet->getRowDimension('1')->setRowHeight(20);
            $sheet->mergeCells('A2:G2')->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i:s'))->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $headerRow = 4;
            $sheet->setCellValue('A'.$headerRow, 'No');
            $sheet->setCellValue('B'.$headerRow, 'Tanggal Pengajuan');
            $sheet->setCellValue('C'.$headerRow, 'Nama Pendatang');
            $sheet->setCellValue('D'.$headerRow, 'NIK');
            $sheet->setCellValue('E'.$headerRow, 'Keperluan');
            $sheet->setCellValue('F'.$headerRow, 'Status');
            $sheet->setCellValue('G'.$headerRow, 'Nomor Surat');
            $sheet->getStyle('A'.$headerRow.':G'.$headerRow)->applyFromArray($styleHeader);
            return $headerRow;
        };

        $populateData = function(Worksheet $sheet, $logs, $startRow, $style) {
            $no = 1;
            $row = $startRow;
            foreach ($logs as $log) {
                $sheet->setCellValue('A'.$row, $no++);
                $sheet->setCellValue('B'.$row, $log->TanggalPengajuan);
                $sheet->setCellValue('C'.$row, $log->nama_pendatang);
                $sheet->setCellValueExplicit('D'.$row, $log->nik_pendatang, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('E'.$row, $log->nama_keperluan);
                $sheet->setCellValue('F'.$row, $log->StatusPengajuan);
                $sheet->setCellValue('G'.$row, $log->NomorSurat);
                $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray($style);
                $row++;
            }
            return $row;
        };

        $sheetSemua = $spreadsheet->getActiveSheet();
        $sheetSemua->setTitle('Semua Log');
        $headerRow = $createHeader($sheetSemua, $data['judul']);
        $currentRow = $headerRow + 1;
        $no = 1;
        foreach ($data['logs'] as $log) {
            $sheetSemua->setCellValue('A'.$currentRow, $no++);
            $sheetSemua->setCellValue('B'.$currentRow, $log->TanggalPengajuan);
            $sheetSemua->setCellValue('C'.$currentRow, $log->nama_pendatang);
            $sheetSemua->setCellValueExplicit('D'.$currentRow, $log->nik_pendatang, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetSemua->setCellValue('E'.$currentRow, $log->nama_keperluan);
            $sheetSemua->setCellValue('F'.$currentRow, $log->StatusPengajuan);
            $sheetSemua->setCellValue('G'.$currentRow, $log->NomorSurat);
            
            $status = strtolower($log->StatusPengajuan);
            $styleToApply = $styleData; 
            if ($status == 'ditolak') {
                $styleToApply = $styleDitolak;
            } else if ($status == 'diverifikasi' || $status == 'terverifikasi' || $status == 'disetujui') {
                $styleToApply = $styleDiverifikasi;
            } else if ($status == 'pending' || $status == 'menunggu verifikasi' || $status == 'proses') {
                $styleToApply = $stylePending;
            }
            
            $sheetSemua->getStyle('A'.$currentRow.':G'.$currentRow)->applyFromArray($styleToApply);
            $currentRow++;
        }

        // Menambahkan keterangan warna
        $legendRow = $currentRow + 2;
        $sheetSemua->setCellValue('B'.$legendRow, 'Keterangan Warna:')->getStyle('B'.$legendRow)->getFont()->setBold(true);
        $sheetSemua->getStyle('C'.($legendRow + 1))->applyFromArray($styleDiverifikasi);
        $sheetSemua->setCellValue('B'.($legendRow + 1), 'Diverifikasi / Disetujui');
        $sheetSemua->getStyle('C'.($legendRow + 2))->applyFromArray($stylePending);
        $sheetSemua->setCellValue('B'.($legendRow + 2), 'Pending / Proses');
        $sheetSemua->getStyle('C'.($legendRow + 3))->applyFromArray($styleDitolak);
        $sheetSemua->setCellValue('B'.($legendRow + 3), 'Ditolak');

        // 2. MEMBUAT SHEET "DIVERIFIKASI"
        $sheetDiverifikasi = new Worksheet($spreadsheet, 'Diverifikasi');
        $spreadsheet->addSheet($sheetDiverifikasi, 1);
        $headerRowDiverifikasi = $createHeader($sheetDiverifikasi, 'Laporan Log Diverifikasi');
        $populateData($sheetDiverifikasi, $logsByStatus['diverifikasi'], $headerRowDiverifikasi + 1, $styleDiverifikasi);

        // 3. MEMBUAT SHEET "PENDING"
        $sheetPending = new Worksheet($spreadsheet, 'Pending');
        $spreadsheet->addSheet($sheetPending, 2);
        $headerRowPending = $createHeader($sheetPending, 'Laporan Log Pending');
        $populateData($sheetPending, $logsByStatus['pending'], $headerRowPending + 1, $stylePending);

        // 4. MEMBUAT SHEET "DITOLAK"
        $sheetDitolak = new Worksheet($spreadsheet, 'Ditolak');
        $spreadsheet->addSheet($sheetDitolak, 3);
        $headerRowDitolak = $createHeader($sheetDitolak, 'Laporan Log Ditolak');
        $populateData($sheetDitolak, $logsByStatus['ditolak'], $headerRowDitolak + 1, $styleDitolak);

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            foreach (range('A', 'G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
        
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $filename = "laporan-log-surat-".date('Ymd').".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    private function _generate_excel_rekap_lingkungan($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $styleJudul = ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]];
        $styleHeader = ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD3D3D3']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER], 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $styleData = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];

        $sheet->mergeCells('A1:D1')->setCellValue('A1', strtoupper($data['judul']))->getStyle('A1')->applyFromArray($styleJudul);
        $sheet->getRowDimension('1')->setRowHeight(20);
        $sheet->mergeCells('A2:D2')->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i:s'))->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerRow = 4;
        $sheet->setCellValue('A'.$headerRow, 'No');
        $sheet->setCellValue('B'.$headerRow, 'Nama Lingkungan');
        $sheet->setCellValue('C'.$headerRow, 'Nama Kepala Lingkungan');
        $sheet->setCellValue('D'.$headerRow, 'Jumlah Pendatang Aktif');
        $sheet->getStyle('A'.$headerRow.':D'.$headerRow)->applyFromArray($styleHeader);

        $row = $headerRow + 1;
        $no = 1;
        foreach ($data['rekap'] as $r) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $r->NamaLingkungan);
            $sheet->setCellValue('C'.$row, $r->NamaKaling);
            $sheet->setCellValue('D'.$row, $r->JumlahPendatang);
            $row++;
        }

        $lastRow = $row - 1;
        if ($lastRow >= ($headerRow + 1)) {
            $sheet->getStyle('A'.($headerRow + 1).':D'.$lastRow)->applyFromArray($styleData);
        }
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
       
        $writer = new Xlsx($spreadsheet);
        $filename = "laporan-rekap-lingkungan-".date('Ymd').".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}