<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'LoginModel', 'DaftarModel', 'PendatangModel', 'PengajuanModel']);
        $this->validasi->validasiakun();
        $this->check_mandatory_update();
        $this->load->helper(['url', 'profile']);
    }

    private function check_mandatory_update()
    {
        $current_method = $this->router->fetch_method();
        if ($current_method === 'first_time_setup' || !$this->session->userdata('NIK')) {
            return;
        }

        $nik = $this->session->userdata('NIK');
        $user_login = $this->LoginModel->get_by_nik($nik);

        if ($user_login && $user_login->WajibGantiPW == 1 && $this->uri->segment(1) !== 'dashboard' && $this->uri->segment(1) !== '') {
            $this->session->set_flashdata('error', 'Anda harus memperbarui password dan lokasi Anda terlebih dahulu.');
            redirect('dashboard');
        }
    }

    private function validasiakun()
    {
        if (!$this->session->userdata('NIK')) {
            redirect('loginHalaman', 'refresh');
        }
    }

    private function prepare_dashboard_data()
    {
        $nik_user = $this->session->userdata('NIK');
        $data = [];
        $id_pj_login = null;
        $jenisAkun = $this->session->userdata('JenisAkun');
        $id_user = $this->session->userdata('id_user');
        if ($jenisAkun === 'Penanggung Jawab') {
            $id_pj_login = $this->session->userdata('id_user');
        }
        $login_details = $this->LoginModel->get_by_nik($nik_user);
        $data['wajibGantiPw'] = $login_details ? $login_details->WajibGantiPW : 0;
        $data['total_pendatang'] = $this->PendatangModel->getTotalPendatangAktif($id_pj_login);

        $daftar_details = $this->DaftarModel->get_by_nik($nik_user);
        $data['latitude_daftar'] = $daftar_details && !empty($daftar_details->latitude_daftar) ? $daftar_details->latitude_daftar : -8.7900;
        $data['longitude_daftar'] = $daftar_details && !empty($daftar_details->longitude_daftar) ? $daftar_details->longitude_daftar : 115.1746;

        $data['total_surat'] = $this->PengajuanModel->get_total_surat_by_role($jenisAkun, $id_user);
        $data['total_pending'] = $this->PendatangModel->getTotalPendatangPending();
        $data['total_kaling'] = $this->DaftarModel->getTotalKaling();
        $data['total_pj'] = $this->DaftarModel->getTotalPJ();
        $data['total_pendaftar_baru'] = $this->DaftarModel->getTotalPendaftarBaru();

        $stats = $this->PendatangModel->get_bulan_stats($id_pj_login);
        $chart_data_values = array_fill(0, 12, 0);
        foreach ($stats as $row) {
            if (isset($chart_data_values[(int)$row['bulan'] - 1])) {
                $chart_data_values[(int)$row['bulan'] - 1] = (int)$row['jumlah'];
            }
        }
        $chart_labels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        $data['chart_labels'] = json_encode($chart_labels);
        $data['chart_data'] = json_encode($chart_data_values);

        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        return $data;
    }

    public function first_time_setup()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required|numeric');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard');
        } else {
            $nik = $this->session->userdata('NIK');
            $hashed_password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

            $this->LoginModel->update($nik, ['Password' => $hashed_password, 'WajibGantiPW' => 0]);
            $this->DaftarModel->update_by_nik($nik, [
                'latitude_daftar' => $this->input->post('latitude'),
                'longitude_daftar' => $this->input->post('longitude')
            ]);

            $this->session->set_userdata('WajibGantiPW', 0);
            $this->session->set_flashdata('success', 'Akun berhasil diperbarui. Selamat datang!');
            redirect('dashboard');
        }
    }

    public function get_chart_data()
    {
        $id_pj_login = null;
        if ($this->session->userdata('JenisAkun') === 'Penanggung Jawab') {
            $id_pj_login = $this->session->userdata('id_user');
        }

        $type = $this->input->get('type');

        if ($type == 'tahun') {
            $stats = $this->PendatangModel->get_tahun_stats($id_pj_login);
            $labels = array_column($stats, 'tahun');
            $data = array_column($stats, 'jumlah');
        } else {
            $stats = $this->PendatangModel->get_bulan_stats($id_pj_login);
            $labels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            $data = array_fill(0, 12, 0);
            foreach ($stats as $row) {
                $data[(int)$row['bulan'] - 1] = (int)$row['jumlah'];
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


    public function index()
    {
        $data = $this->prepare_dashboard_data();
        $profile_data = get_profile_data();
        $data = array_merge($data, $profile_data);
        $this->load->view('dashboard', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
}
