<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'DaftarModel', 'LoginModel']);
        $this->load->helper(['url', 'form', 'profile']);
        $this->load->library('session');
        $this->validasi->validasiakun();
    }

    public function index()
    {
        $nik_user = $this->session->userdata('NIK');
        if (!$nik_user) {
            redirect('login');
        }

        $data['profile'] = $this->get_complete_profile($nik_user);

        if (!$data['profile']) {
            $this->session->set_flashdata('error', 'Data profil tidak ditemukan');
            redirect('dashboard');
        }

        $is_admin = $this->LoginModel->isAdmin($nik_user);
        if (!$is_admin && isset($data['profile']->StatusAktivasi) && $data['profile']->StatusAktivasi !== 'Aktif') {
            $this->session->set_flashdata('error', 'Akun Anda belum terverifikasi');
            redirect('dashboard');
        }

        $data = array_merge($data, get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $data['konten'] = $this->load->view('profilePage/profile', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    private function buatNamaFile()
    {
        $kata = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        $namafile = substr(str_shuffle($kata), 0, 6);
        return $namafile;
    }

    public function uploadPhoto()
    {
        header('Content-Type: application/json');

        $nik_user = $this->session->userdata('NIK');
        if (!$nik_user) {
            echo json_encode(['status' => 'error', 'message' => 'Session expired. Silakan login kembali.']);
            return;
        }

        if (empty($_FILES['fotoProfil']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang dipilih']);
            return;
        }

        try {
            $config['upload_path'] = FCPATH . 'uploads/profile/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = $this->buatNamaFile() . '.' . pathinfo($_FILES['fotoProfil']['name'], PATHINFO_EXTENSION);
            $config['overwrite'] = true;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('fotoProfil')) {
                throw new Exception($this->upload->display_errors('', ''));
            }

            $filename = $this->upload->data('file_name');

            $old_profile = $this->DaftarModel->get_by_nik($nik_user);

            if ($old_profile && !empty($old_profile->FotoProfil)) {
                $old_file_path = FCPATH . 'uploads/profile/' . $old_profile->FotoProfil;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }

            $updated = $this->DaftarModel->updatePhoto($nik_user, $filename);

            if (!$updated) {
                throw new Exception('Gagal menyimpan nama file foto ke database.');
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Foto profil berhasil diperbarui',
                'photo_url' => base_url('uploads/profile/' . $filename)
            ]);
        } catch (Exception $e) {
            if (isset($filename) && file_exists(FCPATH . 'uploads/profile/' . $filename)) {
                unlink(FCPATH . 'uploads/profile/' . $filename);
            }
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function deletePhoto()
    {
        header('Content-Type: application/json');
        $nik_user = $this->session->userdata('NIK');
        if (!$nik_user) {
            echo json_encode(['status' => 'error', 'message' => 'Session expired']);
            return;
        }

        try {
            $profile = $this->DaftarModel->get_by_nik($nik_user);

            if ($profile && !empty($profile->FotoProfil)) {
                $file_path = FCPATH . 'uploads/profile/' . $profile->FotoProfil;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }

                $this->DaftarModel->deletePhoto($nik_user);

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Foto profil berhasil dihapus',
                    'photo_url' => base_url('assets/img/default-profile.jpg')
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Tidak ada foto profil untuk dihapus']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus foto.']);
        }
    }

    public function editProfile()
    {
        $nik_user = $this->session->userdata('NIK');
        if (!$nik_user) {
            redirect('login');
        }

        $data['profile'] = $this->get_complete_profile($nik_user);
        if (!$data['profile']) {
            redirect('dashboard');
        }

        $data = array_merge($data, get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $data['konten'] = $this->load->view('profilePage/editProfile', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function edit()
    {
        $this->editProfile();
    }

    public function save()
    {
        $nik_user = $this->session->userdata('NIK');
        if (!$nik_user) {
            redirect('login');
        }

        $errors = $this->validateProfileData();
        if (!empty($errors)) {
            $this->session->set_flashdata('error', implode('<br>', $errors));
            redirect('profile/editProfile');
            return;
        }


        $data = [
            'NamaLengkap' => trim($this->input->post('namaLengkap')),
            'Alamat'      => trim($this->input->post('alamat')),
            'Telp'        => trim($this->input->post('telp')),
            'Email'       => trim($this->input->post('email'))
        ];

        // Memanggil fungsi yang sudah diperbaiki
        if ($this->is_email_exists_in_master($data['Email'], $nik_user)) {
            $this->session->set_flashdata('error', 'Email sudah digunakan pengguna lain');
            redirect('profile/editProfile');
            return;
        }

        $this->db->trans_start();
        $this->DaftarModel->update_by_nik($nik_user, $data);
        if ($this->LoginModel->get_by_nik($nik_user)) {
            // Hanya update kolom yang relevan di tb_login
            $login_update_data = ['NamaLengkap' => $data['NamaLengkap']];
            // Jika tb_login punya kolom email, tambahkan di sini. Jika tidak, hapus baris di bawah.
            // $login_update_data['Email'] = $data['Email']; 
            $this->LoginModel->updateLogin($nik_user, $login_update_data);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_userdata('NamaLengkap', $data['NamaLengkap']);
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profil.');
        }

        redirect('profile');
    }

    private function validateProfileData()
    {
        $errors = [];
        // Logika validasi Anda di sini...
        return $errors;
    }

    private function get_complete_profile($nik)
    {
        $profile_daftar = $this->DaftarModel->get_by_nik($nik);
        if (!$profile_daftar) {
            $profile_login = $this->LoginModel->get_by_nik($nik);
            if ($profile_login) {
                $profile_login->StatusAktivasi = 'Aktif';
                $profile_login->FotoProfil = null;
                return $profile_login;
            }
            return null;
        }

        $complete_profile = $profile_daftar;
        $profile_login = $this->LoginModel->get_by_nik($nik);
        if ($profile_login) {
            $complete_profile->JenisAkun = $profile_login->JenisAkun ?? $complete_profile->JenisAkun;
            if ($complete_profile->JenisAkun === 'Admin') {
                $complete_profile->StatusAktivasi = 'Aktif';
            }
        }

        if (!isset($complete_profile->FotoProfil)) {
            $complete_profile->FotoProfil = null;
        }

        return $complete_profile;
    }
    private function is_email_exists_in_master($email, $current_nik)
    {
        return $this->DaftarModel->is_email_exists($email, $current_nik);
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
    public function gantiPassword()
    {
        $this->validasi->validasiakun();

        $data = array_merge(get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');

        $data['konten'] = $this->load->view('profilePage/gantiPassword', $data, TRUE);
        $this->load->view('dashboard', $data);
    }


    public function proses_ganti_password()
    {
        $this->validasi->validasiakun();
        $this->load->library('form_validation');


        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|trim|min_length[8]|matches[konfirmasi_password]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile/gantiPassword');
        } else {
            $nik_user = $this->session->userdata('NIK');
            $password_lama = $this->input->post('password_lama');
            $password_baru = $this->input->post('password_baru');

            $user = $this->DaftarModel->get_by_nik($nik_user);

            if (!$user) {
                $user = $this->LoginModel->get_by_nik($nik_user);
            }

            if ($user && $user->Password == $password_lama) {

                $this->db->trans_start();

                if ($this->DaftarModel->get_by_nik($nik_user)) {
                    $this->db->where('NIK', $nik_user)->update('tb_daftar', ['Password' => $password_baru]);
                }

                if ($this->LoginModel->get_by_nik($nik_user)) {
                    $this->db->where('NIK', $nik_user)->update('tb_login', ['Password' => $password_baru]);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan database. Gagal mengubah password.');
                    redirect('profile/gantiPassword');
                } else {
                    $this->session->set_flashdata('success', 'Password Anda berhasil diubah.');
                    redirect('profile');
                }
            } else {
                $this->session->set_flashdata('error', 'Password Lama yang Anda masukkan salah.');
                redirect('profile/gantiPassword');
            }
        }
    }
}
