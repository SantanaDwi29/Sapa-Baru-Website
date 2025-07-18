<?php defined('BASEPATH') or exit('No direct script access allowed');

class LoginHalaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
    }

    public function index()
    {
        $this->load->view('halaman_login');
        
    }

    public function proseslogin()
{
    $NIK = $this->input->post('NIK');
    $Password = $this->input->post('Password');

    if (empty($NIK) || empty($Password)) {
        $this->session->set_flashdata('pesan', 'NIK dan password harus diisi');
        redirect('loginHalaman', 'refresh');
        return;
    }

    $login_data = $this->LoginModel->cekLoginNIK($NIK);

    if ($login_data) {
        if (!password_verify($Password, $login_data->Password)) {
        $this->session->set_flashdata('error', 'NIK atau Password salah.');
        redirect('loginHalaman', 'refresh');
        return; 
    }
    // sebelum dihash
    //     if ($login_data->Password !== $Password) {
    //         $this->session->set_flashdata('error', 'Password salah');
    //         redirect('loginHalaman', 'refresh');
    //         return; // Langsung hentikan jika password salah
    //     }

        $pendaftar = $this->LoginModel->cekPendaftarNIK($NIK);

        if (!$pendaftar) {
            $this->session->set_flashdata('error', 'Data pendaftar tidak ditemukan. Silakan hubungi admin.');
            redirect('loginHalaman', 'refresh');
            return;
        }

        if ($login_data->JenisAkun !== 'Admin') {
            if ($pendaftar->StatusAktivasi === 'Pending') {
                $this->session->set_flashdata('error', 'Akun Anda masih dalam proses verifikasi.');
                redirect('loginHalaman', 'refresh');
                return;
            }
            if ($pendaftar->StatusAktivasi === 'Ditolak') {
                $this->session->set_flashdata('error', 'Akun Anda ditolak. Hubungi admin.');
                redirect('loginHalaman', 'refresh');
                return;
            }
        }
        $session_data = array(
            'idLogin'     => $login_data->idLogin,
            'id_user'     => $pendaftar->idDaftar,     
            'NIK'         => $pendaftar->NIK,         
            'JenisAkun'   => $pendaftar->JenisAkun, 
            'NamaLengkap' => $pendaftar->NamaLengkap 
        );
        $this->session->set_userdata($session_data);


        switch ($login_data->JenisAkun) {
            case 'Admin':
                redirect('dashboard', 'refresh'); 
                break;
            case 'Kepala Lingkungan': 
                redirect('dashboard', 'refresh');
                break;
            case 'Penanggung Jawab': 
                redirect('dashboard', 'refresh');
                break;
            default:
                redirect('loginHalaman', 'refresh');
                break;
        }

    } else {
        $this->session->set_flashdata('error', 'NIK tidak terdaftar. Silakan daftar terlebih dahulu.');
        redirect('loginHalaman', 'refresh');
    }
}
    
}