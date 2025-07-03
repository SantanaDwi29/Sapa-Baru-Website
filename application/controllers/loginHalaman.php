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
            if ($login_data->Password === $Password) {
                if ($login_data->JenisAkun !== 'Admin') {
                    $pendaftar = $this->LoginModel->cekPendaftarNIK($NIK);

                    if (!$pendaftar) {
                        $this->session->set_flashdata('error', 'Data pendaftar tidak ditemukan. Silakan hubungi admin.');
                        redirect('loginHalaman', 'refresh');
                        return;
                    }

                    if ($pendaftar->StatusAktivasi === 'Pending') {
                        $this->session->set_flashdata('error', 'Akun anda masih dalam proses verifikasi. Silakan tunggu atau hubungi admin.');
                        redirect('loginHalaman', 'refresh');
                        return;
                    }

                    if ($pendaftar->StatusAktivasi === 'Ditolak') {
                        $this->session->set_flashdata('error', 'Akun anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.');
                        redirect('loginHalaman', 'refresh');
                        return;
                    }
                }

                $session_data = array(
                    'idLogin' => $login_data->idLogin,
                    'NIK' => $login_data->NIK,
                    'JenisAkun' => $login_data->JenisAkun,
                    'NamaLengkap' => $login_data->NamaLengkap
                );
                $this->session->set_userdata($session_data);

                switch ($login_data->JenisAkun) {
                    case 'Admin':
                        redirect('dashboard/admin', 'refresh');
                        break;
                    case 'Kaling':
                        redirect('dashboard/kaling', 'refresh');
                        break;
                    case 'PJ':
                        redirect('dashboard/pj', 'refresh');
                        break;
                    default:
                        redirect('dashboard', 'refresh');
                        break;
                }
            } else {
                $this->session->set_flashdata('error', 'Password salah');
                redirect('loginHalaman', 'refresh');
            }
        } else {
            $this->session->set_flashdata('error', 'NIK tidak terdaftar. Silakan daftar terlebih dahulu.');
            redirect('loginHalaman', 'refresh');
        }
    }
    
}