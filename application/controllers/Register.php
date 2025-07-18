<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenanggungJawab extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'DaftarModel', 'LoginModel']);
        $this->load->helper(['form', 'profile']);
        $this->validasi->validasiakun();
    }

    public function index()
    {
        $data['PJ'] = $this->DaftarModel->get_all_pj();
        $data = array_merge($data, get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $data['konten'] = $this->load->view('kaling/penanggungJawab', $data, TRUE);
        $data['table'] = $this->load->view('kaling/tbPenanggungJawab', $data, TRUE);

        $this->load->view('dashboard', $data);
    }

    public function save()
    {
        $idDaftar = $this->input->post('id_daftar');
        $NamaLengkap = trim($this->input->post('namaLengkap'));
        $NIK = trim($this->input->post('nik'));
        $Alamat = trim($this->input->post('alamat'));
        $Telp = trim($this->input->post('telp'));
        $Email = trim($this->input->post('email'));
        $Password = $this->input->post('password');

        if (empty($NamaLengkap) || empty($Email) || empty($Telp) || empty($NIK)) {
            $this->session->set_flashdata('error', 'Nama, NIK, Telepon, dan Email wajib diisi.');
            redirect('PenanggungJawab');
            return;
        }

        $data_update = [
            'NamaLengkap' => $NamaLengkap,
            'NIK' => $NIK,
            'Alamat' => $Alamat,
            'Telp' => $Telp,
            'Email' => $Email,
            'JenisAkun' => 'Penanggung Jawab',
            'latitude_daftar' => $this->input->post('latitude_daftar'),
            'longitude_daftar' => $this->input->post('longitude_daftar')
        ];

        try {
            if (empty($idDaftar)) {
                if (empty($Password)) {
                    $this->session->set_flashdata('error', 'Password wajib diisi untuk PJ baru.');
                    redirect('PenanggungJawab');
                    return;
                }

                $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
                $data_update['Password'] = $hashed_password;
                $data_update['StatusAktivasi'] = 'Pending';

                $data_login = [
                    'NamaLengkap' => $NamaLengkap,
                    'NIK' => $NIK,
                    'JenisAkun' => 'Penanggung Jawab',
                    'Password' => $hashed_password,
                    'WajibGantiPW' => 1
                ];

                $this->db->trans_start();
                $this->DaftarModel->insert($data_update);
                $this->LoginModel->insertToLogin($data_login);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Gagal menyimpan data ke database.');
                }

                $this->kirim_email_notifikasi($Email, $NamaLengkap, $NIK, $Password, 'Penanggung Jawab');
                $this->session->set_flashdata('success', 'Data PJ berhasil ditambahkan dan notifikasi email telah dikirim.');

            } else {
                $login_update_data = ['NamaLengkap' => $NamaLengkap];
                if (!empty($Password)) {
                    $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
                    $data_update['Password'] = $hashed_password;
                    $login_update_data['Password'] = $hashed_password;
                }
                
                $this->db->trans_start();
                $this->DaftarModel->update($idDaftar, $data_update);
                $this->LoginModel->update($NIK, $login_update_data);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Gagal memperbarui data.');
                }
                $this->session->set_flashdata('success', 'Data penanggung jawab berhasil diperbarui.');
            }
            redirect('PenanggungJawab');

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('PenanggungJawab');
        }
    }

    private function kirim_email_notifikasi($Email, $NamaLengkap, $NIK, $Password, $JenisAkun)
    {
        $this->load->library('email');
        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'smtp.gmail.com',
            'smtp_user'   => 'dodokss113@gmail.com',
            'smtp_pass'   => 'jhckccmzmiokuknz',
            'smtp_port'   => 587,
            'smtp_crypto' => 'tls',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
        ];
        $this->email->initialize($config);
        $this->email->from('dodoks113@gmail.com', 'Admin Sapa Baru');
        $this->email->to($Email);
        $this->email->subject('Akun ' . $JenisAkun . ' Anda Telah Dibuatkan - Sapa Baru');

        $message = "
        <html>
        <head>
            <title>Pendaftaran Akun Berhasil</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f8fafc; }
                .container { max-width: 650px; margin: 20px auto; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; font-weight: 700; }
                .content { padding: 30px; color: #374151; }
                .info-box { background: #fafafa; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #e5e7eb; }
                .info-box h4 { margin-top: 0; color: #1f2937; font-size: 16px; }
                .info-list { list-style: none; padding-left: 0; margin: 15px 0; }
                .info-list li { margin: 12px 0; padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
                .info-list li:last-child { border-bottom: none; }
                .label { font-weight: 600; color: #374151; }
                .value { color: #6b7280; }
                .password-code { background: #fef3c7; padding: 8px 16px; border-radius: 6px; font-family: 'Courier New', monospace; font-size: 16px; font-weight: bold; color: #92400e; border: 1px solid #fcd34d; }
                .status-pending { background: #fff7ed; border-left: 4px solid #f59e0b; padding: 20px; border-radius: 8px; }
                .status-badge { background: #f59e0b; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
                .footer { text-align: center; color: #6b7280; margin-top: 30px; padding: 20px; font-size: 13px; background: #f9fafb; border-top: 1px solid #e5e7eb; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'><h1>Selamat Datang di Sapa Baru!</h1></div>
                <div class='content'>
                    <p><strong>Halo, " . htmlspecialchars($NamaLengkap) . "!</strong></p>
                    <p>Sebuah akun " . htmlspecialchars($JenisAkun) . " telah dibuat untuk Anda oleh Admin di sistem <strong>Sapa Baru</strong>.</p>
                    <div class='info-box'>
                        <h4>📋 Informasi Login Sementara Anda:</h4>
                        <ul class='info-list'>
                            <li><span class='label'>NIK:</span><span class='value'>" . htmlspecialchars($NIK) . "</span></li>
                            <li><span class='label'>Email:</span><span class='value'>" . htmlspecialchars($Email) . "</span></li>
                            <li><span class='label'>Password Sementara:</span><span class='password-code'>" . htmlspecialchars($Password) . "</span></li>
                        </ul>
                    </div>
                    <div class='info-box status-pending'>
                        <h4>⏳ Status Akun Anda: MENUNGGU VERIFIKASI</h4>
                        <p>Setelah akun diaktivasi oleh Admin, Anda dapat login. Anda akan diminta untuk mengganti password ini saat pertama kali login.</p>
                    </div>
                </div>
                <div class='footer'><p>© " . date('Y') . " Sapa Baru</p></div>
            </div>
        </body>
        </html>";
    
        $this->email->message($message);

        if (!$this->email->send()) {
            log_message('error', 'Email notifikasi PJ gagal dikirim ke ' . $Email . ': ' . $this->email->print_debugger());
        }
    }

    public function get($id)
    {
        $data_pj = $this->DaftarModel->get_by_id($id);
        if ($data_pj) {
            echo json_encode($data_pj);
        } else {
            show_404();
        }
    }

    public function delete($id)
    {
        try {
            $this->db->trans_start();
            $pj_data = $this->DaftarModel->get_by_id($id);
            if($pj_data){
                $this->DaftarModel->delete($id);
                $this->LoginModel->deleteFromLogin($pj_data->NIK);
            }
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus data dari database.');
            }
            $this->session->set_flashdata('success', 'Data pengguna berhasil dihapus');

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        redirect('PenanggungJawab');
    }
    
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
}