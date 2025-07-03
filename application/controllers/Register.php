<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['DaftarModel', 'LoginModel']);
        $this->load->library('email');
    }

    public function index()
    {
        $this->load->view('register');
    }

    function buatpwd()
    {
        $kata = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        $Password = substr(str_shuffle($kata), 0, 6);
        return $Password;
    }

    public function cekNIK()
    {
        $NIK = $this->input->post('NIK');
        $this->db->where('NIK', $NIK);
        $query = $this->db->get('tb_daftar');

        echo ($query->num_rows() > 0) ? 'exists' : 'available';
    }

    public function prosesregister()
    {
        $JenisAkun   = $this->input->post('JenisAkun');
        $NamaLengkap = $this->input->post('NamaLengkap');
        $Telp        = $this->input->post('Telp');
        $Email       = $this->input->post('Email');
        $Alamat      = $this->input->post('Alamat');
        $NIK         = $this->input->post('NIK');
        $Password    = $this->buatpwd();

        $data_daftar = [
            'JenisAkun'      => $JenisAkun,
            'NamaLengkap'    => $NamaLengkap,
            'Telp'           => $Telp,
            'Email'          => $Email,
            'Alamat'         => $Alamat,
            'NIK'            => $NIK,
            'StatusAktivasi' => 'Pending',
            'Password'       => $Password
        ];

        $data_login = [
            'JenisAkun'   => $JenisAkun,
            'NamaLengkap' => $NamaLengkap,
            'NIK'         => $NIK,
            'Password'    => $Password
        ];


        $inserted = $this->DaftarModel->insert($data_daftar);

        if (!$inserted) {
            $this->session->set_flashdata('error', 'Registrasi gagal! Silakan coba lagi.');
            redirect('Register', 'refresh');
            return;
        }
        // $this->db->trans_start();
        // $this->DaftarModel->insert($data_daftar);
        // $this->LoginModel->insertToLogin($data_login);
        // $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Registrasi gagal! Silakan coba lagi.');
            redirect('Register', 'refresh');
        } else {
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
                'smtp_debug'   => 2
            ];

            $this->email->initialize($config);
            $this->email->clear(TRUE);

            // Set from dengan benar
            $this->email->from('dodoks113@gmail.com', 'Sapa Baru');
            $this->email->to($Email);
            $this->email->subject('Pendaftaran Akun Berhasil - Sapa Baru');

            $message = "
            <html>
            <head>
                <title>Pendaftaran Akun Berhasil</title>
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
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                    .description {
                        background: #f0f9ff;
                        padding: 20px;
                        border-radius: 8px;
                        border-left: 4px solid #0ea5e9;
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
                    .status-pending {
                        background: #fff7ed; 
                        border-left-color: #f59e0b;
                        border: 1px solid #fed7aa;
                    }
                    .status-pending .status-badge {
                        background: #f59e0b;
                        color: white;
                        padding: 4px 12px;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: bold;
                        display: inline-block;
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
                    .btn {
                        display: inline-block;
                        padding: 12px 24px;
                        background: #667eea;
                        color: white;
                        text-decoration: none;
                        border-radius: 6px;
                        font-weight: 600;
                        margin: 10px 0;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1> Selamat Datang di Sapa Baru!</h1>
                    </div>
                    <div class='content'>
                        <div class='welcome-text'>
                            <strong>Halo, " . htmlspecialchars($NamaLengkap) . "!</strong>
                        </div>
                        
                        <p>Terima kasih telah mendaftar di <strong>Sapa Baru</strong>. Registrasi akun Anda telah berhasil diproses.</p>
                        
                        <div class='description'>
                            <p><strong>Tentang Sapa Baru:</strong></p>
                            <p>Kami menyediakan platform inovatif untuk mendata dan memantau penduduk pendatang di wilayah tertentu, sehingga kepala lingkungan dapat mengetahui informasi terkini tentang penduduk baru dan tempat tinggalnya. Dengan <strong>Sapa Baru</strong>, kami bertujuan meningkatkan efektivitas pelayanan dan keamanan di wilayah tersebut, serta memperkuat hubungan antara penduduk dan pemerintah setempat.</p>
                        </div>
                        
                        <div class='info-box'>
                            <h4>📋 Informasi Login Anda:</h4>
                            <ul class='info-list'>
                                <li>
                                    <span class='label'>NIK:</span>
                                    <span class='value'>" . htmlspecialchars($NIK) . "</span>
                                </li>
                           
                                <li>
                                    <span class='label'>Level Akun:</span>
                                    <span class='value'>" . htmlspecialchars($JenisAkun) . "</span>
                                </li>
                                <li>
                                    <span class='label'>Email:</span>
                                    <span class='value'>" . htmlspecialchars($Email) . "</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class='info-box status-pending'>
                            <h4>⏳ Status Akun Anda:</h4>
                            <p><span class='status-badge'>MENUNGGU VERIFIKASI</span></p>
                            <p>Akun Anda saat ini berstatus <strong>PENDING</strong> dan sedang menunggu verifikasi dari admin. Silakan tunggu hingga admin mengaktifkan akun Anda sebelum dapat melakukan login ke sistem.</p>
                            <p><em>Anda akan mendapat notifikasi via email ketika akun telah diaktivasi.</em></p>
                        </div>

                    </div>
                    
                    <div class='footer'>
                        <p><strong>Terima kasih telah bergabung dengan Sapa Baru!</strong></p>
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

            if ($this->email->send()) {
                $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan cek email Anda untuk informasi login.');
                redirect('LoginHalaman', 'refresh');
            } else {
                $error = $this->email->print_debugger();
                log_message('error', 'Email tidak dapat dikirim: ' . $error);
                $this->session->set_flashdata('error', 'Registrasi berhasil, tetapi gagal mengirim email. Silakan hubungi admin.');
                redirect('Register', 'refresh');
            }
        }
    }
}
