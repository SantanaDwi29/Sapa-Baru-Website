<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'LoginModel', 'DaftarModel', 'PendatangModel']);
        $this->validasi->validasiakun();
    }

    private function validasiakun()
    {
        if (!$this->session->userdata('NIK')) {
            redirect('loginHalaman', 'refresh');
        }
    }

    private function get_profile_data()
    {
        $nik_user = $this->session->userdata('NIK');
        
        if (!$nik_user) {
            return null;
        }
        
        $profile_daftar = $this->DaftarModel->get_by_nik($nik_user);
        $profile_login = $this->LoginModel->get_by_nik($nik_user);
        
        $profile_data = array();
        
        if ($profile_daftar) {
            $profile_data = (array) $profile_daftar;
        }
        
        if ($profile_login) {
            $login_data = (array) $profile_login;
            foreach ($login_data as $key => $value) {
                if (!empty($value)) {
                    $profile_data[$key] = $value;
                }
            }
        }
        
        return (object) $profile_data;
    }

    private function prepare_dashboard_data()
    {
        $profile = $this->get_profile_data();
        
        $data = array();
        
        if ($profile && !empty($profile->FotoProfil)) {
            $photo_path = FCPATH . 'uploads/profile/' . $profile->FotoProfil;
            if (file_exists($photo_path)) {
                $data['hasProfilePhoto'] = true;
                $data['FotoProfil'] = base_url('uploads/profile/' . $profile->FotoProfil);
            } else {
                $data['hasProfilePhoto'] = false;
                $data['FotoProfil'] = base_url('assets/img/default-profile.jpg');
            }
        } else {
            $data['hasProfilePhoto'] = false;
            $data['FotoProfil'] = base_url('assets/img/default-profile.jpg');
        }
        
        $data['total_pendatang'] = $this->PendatangModel->getTotalPendatangAktif();
        $data['total_kaling'] = $this->DaftarModel->getTotalKaling();
        $data['total_pj'] = $this->DaftarModel->getTotalPJ();
        $data['total_pendaftar_baru'] = $this->DaftarModel->getTotalPendaftarBaru();

        $stats = $this->PendatangModel->get_bulan_stats();

        $chart_data_values = array_fill(0, 12, 0);

        foreach ($stats as $row) {
            $bulan_index = (int)$row['bulan'] - 1;
            $jumlah = (int)$row['jumlah'];
            if (isset($chart_data_values[$bulan_index])) {
                $chart_data_values[$bulan_index] = $jumlah;
            }
        }

        $chart_labels = [
            "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
            "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
        ];

        $data['chart_labels'] = json_encode($chart_labels);
        $data['chart_data'] = json_encode($chart_data_values);

        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        
        return $data;
    }

    public function get_chart_data()
    {
        $type = $this->input->get('type');

        if ($type == 'tahun') {
            $stats = $this->PendatangModel->get_tahun_stats();
            $labels = array_column($stats, 'tahun');
            $data = array_column($stats, 'jumlah');
        } else { 
            $stats = $this->PendatangModel->get_bulan_stats();
            $labels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            $data = array_fill(0, 12, 0);
            foreach ($stats as $row) {
                $bulan_index = (int)$row['bulan'] - 1;
                $data[$bulan_index] = (int)$row['jumlah'];
            }
        }

        $response = [
            'labels' => $labels,
            'data' => $data
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    function admin()
    {
        if ($this->session->userdata('JenisAkun') !== 'Admin') {
            redirect('dashboard', 'refresh');
        }
        
        $data = $this->prepare_dashboard_data();
        $this->load->view('dashboard', $data);
    }

    function kaling()
    {
        if ($this->session->userdata('JenisAkun') !== 'Kepala Lingkungan') {
            redirect('dashboard', 'refresh');
        }
        
        $data = $this->prepare_dashboard_data();
        $this->load->view('dashboard', $data);
    }

    function pj()
    {
        if ($this->session->userdata('JenisAkun') !== 'Penanggung Jawab') {
            redirect('dashboard', 'refresh');
        }
        
        $data = $this->prepare_dashboard_data();
        $this->load->view('dashboard', $data);
    }

    function index()
    {
        $data = $this->prepare_dashboard_data();
        $this->load->view('dashboard', $data);
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
}