<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'PendatangModel', 'KeperluanModel', 'PengajuanModel', 'DaftarModel']);
        $this->load->helper('profile');
        $this->validasi->validasiakun();
    }

    public function index()
    {
        $data['Pendatang'] = $this->PendatangModel->get_available_pendatang();
        $data['Keperluan'] = $this->KeperluanModel->getAllKeperluan();
        $data['Pengajuan'] = $this->PengajuanModel->getAllPengajuan();
        $jenisAkun = $this->session->userdata('JenisAkun');
        $id_user = $this->session->userdata('id_user');
        $id_pj_filter = null;
        if ($jenisAkun == 'Penanggung Jawab') {
            $id_pj_filter = $id_user;
        }
        $data['Pendatang'] = $this->PendatangModel->get_available_pendatang($id_pj_filter);
        $data['Pengajuan'] = $this->PengajuanModel->getAllPengajuan($id_pj_filter);

        $data = array_merge($data, get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');

        $data['konten'] = $this->load->view('surat', $data, TRUE);
        $data['table'] = $this->load->view('tbSurat', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function save()
    {
        if ($this->input->method() === 'post') {
            $idPendatang = $this->input->post('id_pendatang');
            $idKeperluan = $this->input->post('id_surat');


            if (empty($idPendatang) || empty($idKeperluan)) {
                $this->session->set_flashdata('error', 'Silakan lengkapi semua pilihan.');
                redirect('Surat');
                return;
            }

            $existing_application = $this->db->get_where('tb_pengajuan', [
                'idPendatang' => $idPendatang,
                'StatusPengajuan' => 'Pending'
            ])->row();

            if ($existing_application) {
                $this->session->set_flashdata('error', 'Pendatang ini sudah memiliki pengajuan surat yang sedang diproses.');
                redirect('Surat');
                return;
            }

            $data = array(
                'idPendatang' => $idPendatang,
                'idKeperluan' => $idKeperluan,
                'NomorSurat'  => '-' // <--- UBAH MENJADI SEPERTI INI
            );

            if ($this->PengajuanModel->insertPengajuan($data)) {
                $this->session->set_flashdata('success', 'Pengajuan surat berhasil disimpan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan pengajuan surat.');
            }
        } else {
            $this->session->set_flashdata('error', 'Metode permintaan tidak diizinkan.');
        }

        redirect('Surat');
    }

    public function add_keperluan()
    {
        $NamaKeperluan = $this->input->post('NamaKeperluan');

        if (!empty($NamaKeperluan)) {
            $data = ['NamaKeperluan' => $NamaKeperluan];
            if ($this->KeperluanModel->insertKeperluan($data)) {
                // Get the last inserted ID for the new option
                $new_id = $this->db->insert_id();
                echo json_encode(['status' => 'success', 'message' => 'Tipe surat berhasil ditambahkan!', 'id' => $new_id, 'name' => $NamaKeperluan]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan tipe surat.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nama tipe surat tidak boleh kosong.']);
        }
    }

    public function get_keperluan_ajax()
    {
        $keperluan_data = $this->KeperluanModel->getAllKeperluan();
        header('Content-Type: application/json');
        echo json_encode($keperluan_data);
    }

    public function get_available_pendatang_ajax()
    {
        // Ambil data session
        $jenisAkun = $this->session->userdata('JenisAkun');
        $id_user = $this->session->userdata('id_user');

        $id_pj_filter = null;
        if ($jenisAkun == 'Penanggung Jawab') {
            $id_pj_filter = $id_user;
        }

        $pendatang_data = $this->PendatangModel->get_available_pendatang($id_pj_filter);

        header('Content-Type: application/json');
        echo json_encode($pendatang_data);
    }

    public function update_status($idPengajuan)
    {
        if ($this->input->method() === 'post') {
            $new_status = $this->input->post('status');
            $update_data = ['StatusPengajuan' => $new_status];

            if ($new_status == 'Terverifikasi') {
                $current_data = $this->PengajuanModel->getPengajuanById($idPengajuan);
                if (empty($current_data['NomorSurat']) || $current_data['NomorSurat'] == '-') {
                    $update_data['NomorSurat'] = $this->generate_nomor_surat();
                }
            } elseif ($new_status == 'Ditolak') {
                $update_data['NomorSurat'] = '-';
            } else {
                $update_data['NomorSurat'] = '-';
            }

            if ($this->PengajuanModel->updatePengajuan($idPengajuan, $update_data)) {
                $this->session->set_flashdata('success', 'Status pengajuan surat berhasil diperbarui menjadi ' . $new_status . '!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui status pengajuan surat.');
            }
        } else {
            $this->session->set_flashdata('error', 'Metode permintaan tidak diizinkan.');
        }
        redirect('Surat');
    }

    public function delete($idPengajuan)
    {
        if ($this->input->method() === 'post') {
            if ($this->PengajuanModel->deletePengajuan($idPengajuan)) {
                $this->session->set_flashdata('success', 'Pengajuan surat berhasil dihapus!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus pengajuan surat.');
            }
        } else {
            $this->session->set_flashdata('error', 'Metode permintaan tidak diizinkan.');
        }
        redirect('Surat');
    }

    private function generate_nomor_surat()
    {
        $prefix = "SRT";
        $date_part = date('Ymd');
        $random_part = strtoupper(bin2hex(random_bytes(3)));
        return $prefix . '/' . $date_part . '/' . $random_part;
    }
    public function cetak($idPengajuan)
    {
        $data['surat'] = $this->PengajuanModel->getPengajuanById($idPengajuan);

        $kepala_lingkungan_list = $this->DaftarModel->get_all_kaling();

        $nama_kaling = '[Nama Kepala Lingkungan Tidak Ditemukan]';

        if (!empty($kepala_lingkungan_list)) {
            $nama_kaling = $kepala_lingkungan_list[0]->NamaKaling;
        }

        $data['nama_kepala_lingkungan'] = $nama_kaling;

        if (empty($data['surat']) || $data['surat']['StatusPengajuan'] !== 'Terverifikasi') {
            $this->session->set_flashdata('error', 'Data surat tidak ditemukan atau belum diverifikasi.');
            redirect('Surat');
            return;
        }

        require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
        $options = new Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $dompdf = new Dompdf\Dompdf($options);

        $html = $this->load->view('cetakSurat', $data, TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream(
            "Surat Pengantar - " . $data['surat']['NamaLengkap'] . ".pdf",
            array("Attachment" => false)
        );
    }
    public function verifikasi($idPengajuan)
    {
        $nomorBaru = $this->generate_nomor_surat();

        if ($this->PengajuanModel->cekNomorSuratAda($nomorBaru)) {

            $this->session->set_flashdata('error', 'Gagal verifikasi: Terjadi duplikasi nomor surat. Coba beberapa saat lagi.');
            redirect('Surat');
            return;
        }

        $update_data = [
            'StatusPengajuan' => 'Terverifikasi',
            'NomorSurat'      => $nomorBaru,
            'TanggalPengajuan' => date('Y-m-d H:i:s')
        ];

        if ($this->PengajuanModel->updatePengajuan($idPengajuan, $update_data)) {
            $this->session->set_flashdata('success', 'Surat berhasil diverifikasi!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memverifikasi surat.');
        }

        redirect('Surat');
    }
    public function tolak($idPengajuan)
    {
        if ($this->input->method() !== 'post') {
            $this->session->set_flashdata('error', 'Metode permintaan tidak diizinkan.');
            redirect('Surat');
        }

        $alasan = $this->input->post('alasan_penolakan');

        if (empty($alasan)) {
            $this->session->set_flashdata('error', 'Gagal menolak surat karena alasan tidak diberikan.');
            redirect('Surat');
        }

        $update_data = [
            'StatusPengajuan' => 'Ditolak',
            'NomorSurat'      => '-',
            'Alasan' => $alasan
        ];

        if ($this->PengajuanModel->updatePengajuan($idPengajuan, $update_data)) {
            $this->session->set_flashdata('success', 'Pengajuan surat telah ditolak.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak pengajuan surat.');
        }

        redirect('Surat');
    }
    function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
}
