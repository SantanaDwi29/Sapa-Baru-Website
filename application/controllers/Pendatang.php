<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendatang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['PendatangModel', 'validasi','DaftarModel']);
        $this->load->helper(['url', 'form','profile']);
        $this->validasi->validasiakun();
    }

    public function index()
    {
        $data['Pendatang'] = $this->PendatangModel->get_all_pendatang();
        $data['NamaKaling'] = $this->DaftarModel->get_all_kaling();
        $data = array_merge($data, get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $data['konten'] = $this->load->view('pendatang', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    // Method baru untuk mendapatkan detail pendatang (untuk modal view)
    public function detail($id = null)
    {
        // Validasi ID
        if (empty($id) || !is_numeric($id)) {
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID tidak valid!'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'ID tidak valid!');
            redirect('pendatang');
            return;
        }

        // Ambil data detail pendatang
        $pendatang = $this->PendatangModel->get_detail_by_id($id);
        
        if (!$pendatang) {
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data pendatang tidak ditemukan!'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan!');
            redirect('pendatang');
            return;
        }

        // Siapkan data untuk response
        $response_data = [
            'status' => 'success',
            'data' => [
                'idPendatang' => $pendatang->idPendatang,
                'NIK' => $pendatang->NIK,
                'NamaLengkap' => $pendatang->NamaLengkap,
                'Telepon' => $pendatang->Telepon,
                'Agama' => $pendatang->Agama,
                'TempatLahir' => $pendatang->TempatLahir,
                'TanggalLahir' => $pendatang->TanggalLahir,
                'JenisKelamin' => $pendatang->JenisKelamin,
                'Golda' => $pendatang->Golda,
                'Provinsi' => $pendatang->Provinsi,
                'Kabupaten' => $pendatang->Kabupaten,
                'Kecamatan' => $pendatang->Kecamatan,
                'Kelurahan' => $pendatang->Kelurahan,
                'RT' => $pendatang->RT,
                'RW' => $pendatang->RW,
                'Alamat' => $pendatang->Alamat,
                'TempatTujuan' => $pendatang->TempatTujuan,
                'Latitude' => $pendatang->Latitude,
                'Longitude' => $pendatang->Longitude,
                'TanggalMasuk' => $pendatang->TanggalMasuk,
                'TanggalKeluar' => $pendatang->TanggalKeluar,
                'StatusTinggal' => $pendatang->StatusTinggal,
                'NamaKaling' => isset($pendatang->NamaKaling) ? $pendatang->NamaKaling : '-',
                'Tujuan' => $pendatang->Tujuan,
                'FotoDiri' => $pendatang->FotoDiri ? base_url('uploads/fotodiri/' . $pendatang->FotoDiri) : '',
                'FotoKTP' => $pendatang->FotoKTP ? base_url('uploads/fotoktp/' . $pendatang->FotoKTP) : ''
            ]
        ];

        // Return JSON response untuk AJAX request
        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($response_data);
            return;
        }

        // Jika bukan AJAX, redirect ke halaman utama
        redirect('pendatang');
    }

    // Method untuk verifikasi pendatang
    public function verifikasi($id = null)
    {
        // Debug log
        log_message('debug', 'Verifikasi called with ID: ' . $id);
        
        // Validasi ID
        if (empty($id) || !is_numeric($id)) {
            log_message('error', 'Invalid ID in verifikasi: ' . $id);
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID tidak valid!'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'ID tidak valid!');
            redirect('pendatang');
            return;
        }

        // Cek apakah data pendatang ada
        $pendatang = $this->PendatangModel->get_by_id($id);
        if (!$pendatang) {
            log_message('error', 'Pendatang not found with ID: ' . $id);
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data pendatang tidak ditemukan!'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan!');
            redirect('pendatang');
            return;
        }

        try {
            // Update status menjadi Aktif
            $data = [
                'StatusTinggal' => 'Aktif',
                'Alasan' => 'Diverifikasi  pada ' . date('Y-m-d H:i:s')
            ];

            log_message('debug', 'Updating pendatang with data: ' . json_encode($data));
            $result = $this->PendatangModel->update($id, $data);
            
            if ($result) {
                log_message('info', 'Pendatang verified successfully: ' . $id);
                $this->session->set_flashdata('success', 'Pendatang berhasil diverifikasi!');
            } else {
                log_message('error', 'Failed to verify pendatang: ' . $id);
                $this->session->set_flashdata('error', 'Gagal memverifikasi pendatang!');
            }

        } catch (Exception $e) {
            log_message('error', 'Exception in verifikasi: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        redirect('pendatang');
    }

    // Method untuk menolak verifikasi pendatang
    public function tolak($id = null)
    {
        // Debug log
        log_message('debug', 'Tolak called with ID from URL: ' . $id);
        log_message('debug', 'POST data: ' . json_encode($this->input->post()));
        
        // Ambil ID dari parameter URL atau POST
        if (empty($id)) {
            $id = $this->input->post('id');
            log_message('debug', 'ID from POST: ' . $id);
        }

        // Validasi ID
        if (empty($id) || !is_numeric($id)) {
            log_message('error', 'Invalid ID in tolak: ' . $id);
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID tidak valid!'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'ID tidak valid!');
            redirect('pendatang');
            return;
        }

        // Cek apakah data pendatang ada
        $pendatang = $this->PendatangModel->get_by_id($id);
        if (!$pendatang) {
            log_message('error', 'Pendatang not found with ID: ' . $id);
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data pendatang tidak ditemukan!'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan!');
            redirect('pendatang');
            return;
        }

        // Ambil alasan penolakan
        $alasan = $this->input->post('alasan');
        log_message('debug', 'Alasan from POST: ' . $alasan);
        
        if (empty($alasan)) {
            log_message('error', 'Empty alasan in tolak');
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Alasan penolakan wajib diisi!'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi!');
            redirect('pendatang');
            return;
        }

        try {
            // Update status menjadi Ditolak
            $data = [
                'StatusTinggal' => 'Ditolak',
                'Alasan' => $alasan . ' (Ditolak pada ' . date('Y-m-d H:i:s'). ')'
            ];

            log_message('debug', 'Updating pendatang with data: ' . json_encode($data));
            $result = $this->PendatangModel->update($id, $data);
            
            if ($result) {
                log_message('info', 'Pendatang rejected successfully: ' . $id);
                $this->session->set_flashdata('success', 'Pendatang berhasil ditolak!');
            } else {
                log_message('error', 'Failed to reject pendatang: ' . $id);
                $this->session->set_flashdata('error', 'Gagal menolak pendatang!');
            }

        } catch (Exception $e) {
            log_message('error', 'Exception in tolak: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        redirect('pendatang');
    }

    private function buatNamaFile()
    {
        $kata = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        $namafile = substr(str_shuffle($kata), 0, 6); 
        return $namafile . '_' . time(); 
    }

    private function upload_file($uploadFile, $field, $namaFileAcak) 
    {
        if (empty($uploadFile['name'])) {
            return "";
        }

        $extractFile = pathinfo($uploadFile['name']);
        $ekst = strtolower($extractFile['extension']); 
        $newName = $namaFileAcak . "." . $ekst; 

        $uploadPath = '';
        if ($field === 'fotoDiri') {
            $uploadPath = FCPATH . 'uploads/fotodiri';
        } elseif ($field === 'fotoKTP') {
            $uploadPath = FCPATH . 'uploads/fotoktp'; 
        }

        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'jpg|png|jpeg|JPG|PNG|JPEG';
        $config['max_size'] = 5120;
        $config['overwrite'] = true;
        $config['file_name'] = $newName;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', 'Upload gagal: ' . $error);
            return "";
        }

        return $newName;
    }

    public function save()
    {
        $namaFileAcakDiri = $this->buatNamaFile();
        $namaFileAcakKTP = $this->buatNamaFile();
        
        $idPendatang = $this->input->post('id_pendatang');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $rt = $this->input->post('rt');
        $rw = $this->input->post('rw');
        if ($rw === null || $rw === '' || $rw === false) {
            $rw = '0';
            log_message('debug', 'RW was empty, set to: ' . $rw);
        }
        
        // Validasi RT harus numerik
        if (!is_numeric($rt)) {
            log_message('error', 'RT is not numeric: ' . $rt);
            $rt = '0';
        }
        
        // Validasi RW harus numerik
        if (!is_numeric($rw)) {
            log_message('error', 'RW is not numeric: ' . $rw);
            $rw = '0';
        }
        if (empty($latitude)) {
            $latitude = '0';
        }
        if (empty($longitude)) {
            $longitude = '0';
        }

        $data = [
            'idPendatang' => $idPendatang,
            'idKaling' => $this->input->post('id_kaling'),
            'NIK' => $this->input->post('nik'),
            'NamaLengkap' => $this->input->post('nama_lengkap'),
            'Alamat' => $this->input->post('alamat'),
            'Telepon' => $this->input->post('telepon'),
            'TempatLahir' => $this->input->post('tempat_lahir'),
            'TanggalLahir' => $this->input->post('tanggal_lahir'),
            'Tujuan' => $this->input->post('tujuan'),
            'TempatTujuan' => $this->input->post('tempat_tujuan'),
            'TanggalMasuk' => $this->input->post('tanggal_masuk'),
            'TanggalKeluar' => $this->input->post('tanggal_keluar'),
            'StatusTinggal' => 'Pending',
            'JenisKelamin' => $this->input->post('jenis_kelamin'),
            'Golda' => $this->input->post('golda'),
            'Agama' => $this->input->post('agama'),
            'Provinsi' => $this->input->post('provinsi'),
            'Kabupaten' => $this->input->post('kabupaten'),
            'Kecamatan' => $this->input->post('kecamatan'),
            'Kelurahan' => $this->input->post('kelurahan'),
            'RT' => $rt,
            'RW' => $rw,
            'Latitude' => $latitude,  
            'Longitude' => $longitude,
            'Alasan' => '-'
        ];
        log_message('debug', 'Data to be saved - RT: "' . $data['RT'] . '", RW: "' . $data['RW'] . '"');
        log_message('debug', 'Complete data array: ' . print_r($data, true));
        try {
            if (empty($idPendatang)) {
                if (!empty($_FILES['fotoDiri']['name'])) {
                    $FotoDiri = $this->upload_file($_FILES['fotoDiri'], 'fotoDiri', $namaFileAcakDiri);
                    if (!empty($FotoDiri)) {
                        $data['FotoDiri'] = $FotoDiri;
                    }
                }
                
                if (!empty($_FILES['fotoKTP']['name'])) {
                    $FotoKTP = $this->upload_file($_FILES['fotoKTP'], 'fotoKTP', $namaFileAcakKTP);
                    if (!empty($FotoKTP)) {
                        $data['FotoKTP'] = $FotoKTP;
                    }
                }
                
                $result = $this->PendatangModel->insert($data);
                $this->session->set_flashdata('success', 'Data pendatang berhasil ditambahkan');
            } else {
                if (!empty($_FILES['fotoDiri']['name'])) {
                    $old_data = $this->PendatangModel->get_by_id($idPendatang);
                    if ($old_data && $old_data->FotoDiri) {
                        $old_file = FCPATH . 'uploads/fotodiri/' . $old_data->FotoDiri;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                    
                    $FotoDiri = $this->upload_file($_FILES['fotoDiri'], 'fotoDiri', $namaFileAcakDiri);
                    if (!empty($FotoDiri)) {
                        $data['FotoDiri'] = $FotoDiri;
                    }
                }
                
                if (!empty($_FILES['fotoKTP']['name'])) {
                    $old_data = $this->PendatangModel->get_by_id($idPendatang);
                    if ($old_data && $old_data->FotoKTP) {
                        $old_file = FCPATH . 'uploads/fotoktp/' . $old_data->FotoKTP;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                    
                    $FotoKTP = $this->upload_file($_FILES['fotoKTP'], 'fotoKTP', $namaFileAcakKTP);
                    if (!empty($FotoKTP)) {
                        $data['FotoKTP'] = $FotoKTP;
                    }
                }
                
                $result = $this->PendatangModel->update($idPendatang, $data);
                $this->session->set_flashdata('success', 'Data pendatang berhasil diperbarui');
            }
            redirect('pendatang');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('pendatang');
        }
    }
    public function get($id)
{
    // Validasi ID
    if (!$id || !is_numeric($id)) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'ID tidak valid'
        ]);
        return;
    }

    $Pendatang = $this->PendatangModel->get_by_id($id);
    
    if ($Pendatang) {
        header('Content-Type: application/json');
        echo json_encode($Pendatang);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ]);
    }
}
    public function delete($id = null)
    {
        if (!$id) {
            $this->session->set_flashdata('error', 'ID pendatang tidak valid');
            redirect('pendatang');
        }

        $pendatang = $this->PendatangModel->get_foto_by_id($id);
        
        if (!$pendatang) {
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan');
            redirect('pendatang');
        }

        try {
            if (!empty($pendatang->FotoDiri)) {
                $foto_diri_path = FCPATH . 'uploads/fotodiri/' . $pendatang->FotoDiri;
                if (file_exists($foto_diri_path)) {
                    unlink($foto_diri_path);
                }
            }

            if (!empty($pendatang->FotoKTP)) {
                $foto_ktp_path = FCPATH . 'uploads/fotoktp/' . $pendatang->FotoKTP;
                if (file_exists($foto_ktp_path)) {
                    unlink($foto_ktp_path);
                }
            }

            $result = $this->PendatangModel->delete($id);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Data pendatang berhasil dihapus');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data pendatang');
            }

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Data berhasil dihapus' : 'Gagal menghapus data'
            ]);
            return;
        }

        redirect('pendatang');
    }
    public function viewDetail($id = null) {
        if (!$id) {
            show_404();
        }

        // Load model untuk ambil data
        $this->load->model('PendatangModel');

        $data['Pendatang'] = $this->PendatangModel->get_by_id($id);
        $data['NamaKaling'] = $this->DaftarModel->get_all_kaling(); 

        if (!$data['Pendatang']) {
            show_404();
        }
        $data = array_merge($data, get_profile_data());


        $data['konten'] = $this->load->view('viewDetail', $data, TRUE);
        $this->load->view('dashboard', $data);
    }
    function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
    public function archive($id)
    {
        $jenisAkun = $this->session->userdata('JenisAkun');
        if ($jenisAkun != 'Admin' && $jenisAkun != 'Penanggung Jawab') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
            redirect('pendatang');
        }
    
        if ($jenisAkun == 'Penanggung Jawab') {
            $idPJ = $this->session->userdata('id_user'); 
            
            $pendatang = $this->PendatangModel->get_by_id($id); 
            
            if (!$pendatang || !isset($pendatang->idPenanggungJawab) || $pendatang->idPenanggungJawab != $idPJ) {
                 $this->session->set_flashdata('error', 'Aksi ditolak! Anda hanya bisa mengubah data pendatang yang Anda daftarkan.');
                 redirect('pendatang');
                 return; 
            }
        }
    
        $tanggalKeluar = $this->input->post('tanggal_keluar');
        $alasanKeluar = $this->input->post('alasan_keluar');
    
        if (empty($tanggalKeluar) || empty($alasanKeluar)) {
            $this->session->set_flashdata('error', 'Tanggal dan Alasan keluar wajib diisi.');
            redirect('pendatang');
        }
    
        $data = [
            'TanggalKeluar' => $tanggalKeluar,
            'AlasanKeluar' => $alasanKeluar,  
            'StatusTinggal' => 'Tidak Aktif'
        ];
        
        // --- GUNAKAN FUNGSI update() DARI MODEL ---
        $update = $this->PendatangModel->update($id, $data);
    
        if ($update) {
            $this->session->set_flashdata('success', 'Data pendatang berhasil diarsipkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengarsipkan data pendatang.');
        }
    
        redirect('pendatang');
    }
}