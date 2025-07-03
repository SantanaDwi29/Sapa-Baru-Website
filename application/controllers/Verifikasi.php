<?php

class Verifikasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'DaftarModel', 'LoginModel']);
        $this->load->library('email');
        $this->load->helper('profile');
        $this->validasi->validasiakun();
    }

    public function index()
    {
        $data['verifikasi'] = $this->DaftarModel->get_all();
        $data = array_merge($data, get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $data['konten'] = $this->load->view('admin/verifikasi', $data, TRUE);

        $this->load->view('dashboard', $data);
    }

    public function verifikasi($id)
    {
        if (!$id || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'ID tidak valid!');
            redirect('verifikasi');
            return;
        }

        $daftar_data = $this->DaftarModel->get_by_id($id);

        if (!$daftar_data) {
            $this->session->set_flashdata('error', 'Data pendaftar tidak ditemukan!');
            redirect('verifikasi');
            return;
        }

        if ($daftar_data->StatusAktivasi == 'Aktif') {
            $this->session->set_flashdata('error', 'Akun sudah diverifikasi sebelumnya!');
            redirect('verifikasi');
            return;
        }

        if ($daftar_data->StatusAktivasi == 'Ditolak') {
            $this->session->set_flashdata('error', 'Akun ini sudah ditolak sebelumnya!');
            redirect('verifikasi');
            return;
        }

        $this->db->trans_start();

        try {
            $existing_login = $this->LoginModel->cekLoginNIK($daftar_data->NIK);

            if ($existing_login) {
                throw new Exception('NIK sudah terdaftar di sistem login');
            }

            $update_daftar = $this->DaftarModel->update($id, [
                'StatusAktivasi' => 'Aktif',
                'Alasan' => '-'  
            ]);

            if (!$update_daftar) {
                throw new Exception('Gagal mengupdate status verifikasi');
            }

            $login_data = [
                'NIK' => $daftar_data->NIK,
                'Password' => $daftar_data->Password,
                'NamaLengkap' => $daftar_data->NamaLengkap,
                'JenisAkun' => $this->determine_level($daftar_data->JenisAkun)
            ];

            $insert_login = $this->LoginModel->insertToLogin($login_data);

            if (!$insert_login) {
                throw new Exception('Gagal menyimpan data ke tabel login');
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaksi database gagal');
            }

            // Kirim email notifikasi verifikasi berhasil
            $this->send_verification_success_email($daftar_data);

            $this->session->set_flashdata(
                'success',
                'Akun atas nama ' . $daftar_data->NamaLengkap . ' berhasil diverifikasi dan dapat digunakan untuk login!'
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Verifikasi gagal: ' . $e->getMessage());
        }

        redirect('verifikasi');
    }

    public function tolak()
    {
        if ($this->input->method() !== 'post') {
            $this->session->set_flashdata('error', 'Method tidak diizinkan!');
            redirect('verifikasi');
            return;
        }

        $id = $this->input->post('id');
        $alasan = trim($this->input->post('alasan'));

        if (!$id || !is_numeric($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak valid!'
            ]);
            return;
        }

        if (empty($alasan)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Alasan penolakan wajib diisi!'
            ]);
            return;
        }

        $daftar_data = $this->DaftarModel->get_by_id($id);

        if (!$daftar_data) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data pendaftar tidak ditemukan!'
            ]);
            return;
        }

        if ($daftar_data->StatusAktivasi == 'Aktif') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Akun sudah diverifikasi, tidak dapat ditolak!'
            ]);
            return;
        }

        $update_result = $this->DaftarModel->update($id, [
            'StatusAktivasi' => 'Ditolak',
            'Alasan' => $alasan
        ]);

        if ($update_result) {
            // Kirim email notifikasi penolakan
            $this->send_rejection_email($daftar_data, $alasan);

            echo json_encode([
                'status' => 'success',
                'message' => 'Akun atas nama ' . $daftar_data->NamaLengkap . ' berhasil ditolak!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menolak akun!'
            ]);
        }
    }

    public function reset($id)
    {
        if (!$id || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'ID tidak valid!');
            redirect('verifikasi');
            return;
        }

        $daftar_data = $this->DaftarModel->get_by_id($id);

        if (!$daftar_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('verifikasi');
            return;
        }

        $update_result = $this->DaftarModel->update($id, [
            'StatusAktivasi' => 'Pending',
            'Alasan' => ''
        ]);

        if ($update_result) {
            $this->session->set_flashdata('success', 'Status akun berhasil direset ke Pending!');
        } else {
            $this->session->set_flashdata('error', 'Gagal mereset status akun!');
        }

        redirect('verifikasi');
    }

    private function determine_level($jenis_akun)
    {
        switch ($jenis_akun) {
            case 'Kepala Lingkungan':
                return 'Kepala Lingkungan';
            case 'Penanggung Jawab':
                return 'Penanggung Jawab';
            default:
                return 'User';
        }
    }
    
    public function get_pendaftar($id)
    {
        $data = $this->DaftarModel->get_by_id($id);
        if ($data) {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    private function send_verification_success_email($user_data)
    {
        $config = [
            'protocol'     => 'smtp',
            'smtp_host'    => 'smtp.gmail.com',
            'smtp_port'    => 587,
            'smtp_user'    => 'dodokss113@gmail.com',
            'smtp_pass'    => 'jhckccmzmiokuknz', 
            'smtp_crypto'  => 'tls',
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'newline'      => "\r\n",
            'crlf'         => "\r\n",
            'smtp_timeout' => 60,
            'wordwrap'     => TRUE,
            'wrapchars'    => 76,
            'validate'     => FALSE,
            'priority'     => 3,
            'smtp_debug'   => 0 
        ];
        
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        
        $this->email->from('dodoks113@gmail.com', 'Sapa Baru');
        $this->email->to($user_data->Email);
        $this->email->subject('Akun Berhasil Diverifikasi - Sapa Baru');
        
        $message = "
        <html>
        <head>
            <title>Akun Berhasil Diverifikasi</title>
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    line-height: 1.6; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f8fafc;
                }
                .container { 
                    max-width: 650px; 
                    margin: 20px auto; 
                    background: white; 
                    border-radius: 12px; 
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                .header { 
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white; 
                    padding: 30px 20px; 
                    text-align: center; 
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: 700;
                }
                .content { 
                    padding: 30px; 
                    color: #374151;
                }
                .welcome-text {
                    font-size: 18px;
                    margin-bottom: 20px;
                    color: #1f2937;
                }
                .success-box {
                    background: #f0fdf4; 
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid #10b981;
                    margin: 20px 0;
                    font-size: 14px;
                    line-height: 1.7;
                }
                .info-box { 
                    background: #fafafa; 
                    padding: 20px; 
                    margin: 20px 0; 
                    border-radius: 8px; 
                    border: 1px solid #e5e7eb;
                }
                .info-box h4 {
                    margin-top: 0;
                    color: #1f2937;
                    font-size: 16px;
                }
                .info-list {
                    list-style: none; 
                    padding-left: 0;
                    margin: 15px 0;
                }
                .info-list li {
                    margin: 12px 0;
                    padding: 8px 0;
                    border-bottom: 1px solid #f3f4f6;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .info-list li:last-child {
                    border-bottom: none;
                }
                .label {
                    font-weight: 600;
                    color: #374151;
                }
                .value {
                    color: #6b7280;
                }
                .password-code { 
                    background: #fef3c7; 
                    padding: 8px 16px; 
                    border-radius: 6px; 
                    font-family: 'Courier New', monospace; 
                    font-size: 16px; 
                    font-weight: bold;
                    color: #92400e;
                    border: 1px solid #fcd34d;
                }
                .login-button {
                    display: inline-block;
                    padding: 12px 24px;
                    background: #10b981;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: 600;
                    margin: 20px 0;
                    text-align: center;
                }
                .footer { 
                    text-align: center; 
                    color: #6b7280; 
                    margin-top: 30px; 
                    padding: 20px;
                    font-size: 13px;
                    background: #f9fafb;
                    border-top: 1px solid #e5e7eb;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>✅ Akun Anda Telah Diverifikasi!</h1>
                </div>
                <div class='content'>
                    <div class='welcome-text'>
                        <strong>Selamat, " . htmlspecialchars($user_data->NamaLengkap) . "!</strong>
                    </div>
                    
                    <div class='success-box'>
                        <p><strong>🎉 Kabar Baik!</strong></p>
                        <p>Akun Anda di <strong>Sapa Baru</strong> telah berhasil diverifikasi oleh admin dan sekarang sudah aktif. Anda dapat mulai menggunakan sistem kami dengan login menggunakan informasi yang telah diberikan sebelumnya.</p>
                    </div>
                    
                    <div class='info-box'>
                        <h4>🔑 Informasi Login Anda:</h4>
                        <ul class='info-list'>
                            <li>
                                <span class='label'>NIK:</span>
                                <span class='value'>" . htmlspecialchars($user_data->NIK) . "</span>
                            </li>
                            <li>
                                <span class='label'>Password:</span>
                                <span class='password-code'>" . htmlspecialchars($user_data->Password) . "</span>
                            </li>
                            <li>
                                <span class='label'>Level Akun:</span>
                                <span class='value'>" . htmlspecialchars($user_data->JenisAkun) . "</span>
                            </li>
                            <li>
                                <span class='label'>Status:</span>
                                <span style='color: #10b981; font-weight: bold;'>AKTIF</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <p><strong>Akun Anda sudah siap digunakan!</strong></p>
                        <p>Silakan login ke sistem untuk mulai menggunakan layanan Sapa Baru.</p>
                    </div>
                    
                    <div class='info-box'>
                        <h4>📋 Langkah Selanjutnya:</h4>
                        <p>• Login ke sistem menggunakan NIK dan password di atas</p>
                        <p>• Ubah password default Anda setelah login pertama (opsional)</p>
                        <p>• Lengkapi profil Anda jika diperlukan</p>
                        <p>• Mulai gunakan fitur-fitur yang tersedia sesuai level akun Anda</p>
                    </div>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <p>Butuh bantuan? Hubungi admin atau tim support kami.</p>
                        <p><em>Terima kasih atas kesabaran Anda menunggu proses verifikasi.</em></p>
                    </div>
                </div>
                
                <div class='footer'>
                    <p><strong>Selamat bergabung dengan Sapa Baru!</strong></p>
                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
                    <p style='margin-top: 15px; font-size: 12px; color: #9ca3af;'>
                        © 2024 Sapa Baru - Platform Pendataan Penduduk Pendatang
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $this->email->message($message);
        
        if (!$this->email->send()) {
            $error = $this->email->print_debugger();
            log_message('error', 'Email verifikasi tidak dapat dikirim: ' . $error);
        }
    }

    private function send_rejection_email($user_data, $alasan)
    {
        $config = [
            'protocol'     => 'smtp',
            'smtp_host'    => 'smtp.gmail.com',
            'smtp_port'    => 587,
            'smtp_user'    => 'dodokss113@gmail.com',
            'smtp_pass'    => 'jhckccmzmiokuknz', 
            'smtp_crypto'  => 'tls',
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'newline'      => "\r\n",
            'crlf'         => "\r\n",
            'smtp_timeout' => 60,
            'wordwrap'     => TRUE,
            'wrapchars'    => 76,
            'validate'     => FALSE,
            'priority'     => 3,
            'smtp_debug'   => 0 
        ];
        
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        
        $this->email->from('dodoks113@gmail.com', 'Sapa Baru');
        $this->email->to($user_data->Email);
        $this->email->subject('Pemberitahuan Status Pendaftaran - Sapa Baru');
        
        $message = "
        <html>
        <head>
            <title>Pemberitahuan Status Pendaftaran</title>
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    line-height: 1.6; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f8fafc;
                }
                .container { 
                    max-width: 650px; 
                    margin: 20px auto; 
                    background: white; 
                    border-radius: 12px; 
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                .header { 
                    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                    color: white; 
                    padding: 30px 20px; 
                    text-align: center; 
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: 700;
                }
                .content { 
                    padding: 30px; 
                    color: #374151;
                }
                .welcome-text {
                    font-size: 18px;
                    margin-bottom: 20px;
                    color: #1f2937;
                }
                .rejection-box {
                    background: #fef2f2; 
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid #ef4444;
                    margin: 20px 0;
                    font-size: 14px;
                    line-height: 1.7;
                }
                .reason-box { 
                    background: #fafafa; 
                    padding: 20px; 
                    margin: 20px 0; 
                    border-radius: 8px; 
                    border: 1px solid #e5e7eb;
                }
                .reason-box h4 {
                    margin-top: 0;
                    color: #1f2937;
                    font-size: 16px;
                }
                .footer { 
                    text-align: center; 
                    color: #6b7280; 
                    margin-top: 30px; 
                    padding: 20px;
                    font-size: 13px;
                    background: #f9fafb;
                    border-top: 1px solid #e5e7eb;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📋 Pemberitahuan Status Pendaftaran</h1>
                </div>
                <div class='content'>
                    <div class='welcome-text'>
                        <strong>Kepada Yth. " . htmlspecialchars($user_data->NamaLengkap) . ",</strong>
                    </div>
                    
                    <div class='rejection-box'>
                        <p><strong>Pemberitahuan Status Pendaftaran</strong></p>
                        <p>Mohon maaf, setelah melakukan review terhadap data pendaftaran Anda di <strong>Sapa Baru</strong>, kami belum dapat menyetujui pendaftaran akun Anda saat ini.</p>
                    </div>
                    
                    <div class='reason-box'>
                        <h4>📝 Alasan:</h4>
                        <p style='background: #fff; padding: 15px; border-radius: 6px; font-style: italic; border-left: 3px solid #ef4444;'>" . htmlspecialchars($alasan) . "</p>
                    </div>
                    
                    <div class='reason-box'>
                        <h4>🔄 Langkah Selanjutnya:</h4>
                        <p>• Silakan periksa kembali data yang Anda berikan</p>
                        <p>• Pastikan semua informasi yang dimasukkan sudah benar dan lengkap</p>
                        <p>• Anda dapat mendaftar ulang dengan data yang sudah diperbaiki</p>
                        <p>• Jika ada pertanyaan, silakan hubungi admin atau tim support kami</p>
                    </div>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <p>Kami mohon maaf atas ketidaknyamanan ini.</p>
                        <p><em>Terima kasih atas pengertian Anda.</em></p>
                    </div>
                </div>
                
                <div class='footer'>
                    <p><strong>Tim Sapa Baru</strong></p>
                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
                    <p style='margin-top: 15px; font-size: 12px; color: #9ca3af;'>
                        © 2024 Sapa Baru - Platform Pendataan Penduduk Pendatang
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $this->email->message($message);
        
        if (!$this->email->send()) {
            $error = $this->email->print_debugger();
            log_message('error', 'Email penolakan tidak dapat dikirim: ' . $error);
        }
    }
    function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
    
    
}