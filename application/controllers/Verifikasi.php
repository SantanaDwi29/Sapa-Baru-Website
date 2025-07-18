<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
        $data['verifikasi'] = $this->DaftarModel->get_unverified_users();
        
        $data = array_merge($data, get_profile_data());
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $data['konten'] = $this->load->view('admin/verifikasi', $data, TRUE);

        $this->load->view('dashboard', $data);
    }

    public function verifikasi($id)
    {
        if (!$id || !is_numeric($id)) {
            redirect('verifikasi');
            return;
        }

        $daftar_data = $this->DaftarModel->get_by_id($id);
        if (!$daftar_data) {
            $this->session->set_flashdata('error', 'Data pendaftar tidak ditemukan!');
            redirect('verifikasi');
            return;
        }

        $existing_login = $this->LoginModel->cekLoginNIK($daftar_data->NIK);
        if ($existing_login) {
            $this->session->set_flashdata('error', 'Akun ini sudah aktif dan terdaftar di sistem login!');
            redirect('verifikasi');
            return;
        }

        $this->db->trans_start();
        try {
            $this->DaftarModel->update($id, [
                'StatusAktivasi' => 'Aktif',
                'Alasan' => '-'
            ]);

            $login_data = [
                'NIK' => $daftar_data->NIK,
                'Password' => $daftar_data->Password,
                'NamaLengkap' => $daftar_data->NamaLengkap,
                'JenisAkun' => $daftar_data->JenisAkun,
                'WajibGantiPW' => 1
            ];
            $this->LoginModel->insertToLogin($login_data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaksi database gagal.');
            }

            $this->send_verification_success_email($daftar_data);
            $this->session->set_flashdata('success', 'Akun ' . $daftar_data->NamaLengkap . ' berhasil diverifikasi!');
        
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Verifikasi gagal: ' . $e->getMessage());
        }

        redirect('verifikasi');
    }

    public function tolak()
    {
        $id = $this->input->post('id');
        $alasan = trim($this->input->post('alasan'));

        if (!$id || empty($alasan)) {
            echo json_encode(['status' => 'error', 'message' => 'ID atau Alasan tidak valid!']);
            return;
        }

        $daftar_data = $this->DaftarModel->get_by_id($id);
        if (!$daftar_data) {
            echo json_encode(['status' => 'error', 'message' => 'Data pendaftar tidak ditemukan!']);
            return;
        }

        $update_result = $this->DaftarModel->update($id, [
            'StatusAktivasi' => 'Ditolak',
            'Alasan' => $alasan
        ]);

        if ($update_result) {
            $this->send_rejection_email($daftar_data, $alasan);
            echo json_encode(['status' => 'success', 'message' => 'Akun berhasil ditolak!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menolak akun!']);
        }
    }

    public function reset($id)
    {
        if (!$id || !is_numeric($id)) {
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

    private function send_verification_success_email($user_data)
    {
        $config = ['protocol' => 'smtp', 'smtp_host' => 'smtp.gmail.com', 'smtp_user' => 'dodokss113@gmail.com', 'smtp_pass' => 'jhckccmzmiokuknz', 'smtp_port' => 587, 'smtp_crypto' => 'tls', 'mailtype' => 'html', 'charset' => 'utf-8', 'newline' => "\r\n"];
        $this->email->initialize($config);
        $this->email->from('dodoks113@gmail.com', 'Sapa Baru');
        $this->email->to($user_data->Email);
        $this->email->subject('Akun Berhasil Diverifikasi - Sapa Baru');
        
        $message = "
        <html><head><title>Akun Berhasil Diverifikasi</title>
        <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f8fafc; }
            .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
            .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px 20px; text-align: center; }
            .header h1 { margin: 0; font-size: 28px; }
            .content { padding: 30px; color: #374151; }
            .success-box { background: #f0fdf4; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981; margin: 20px 0; }
            .footer { text-align: center; color: #6b7280; margin-top: 30px; padding: 20px; font-size: 13px; background: #f9fafb; border-top: 1px solid #e5e7eb; }
        </style>
        </head><body>
            <div class='container'>
                <div class='header'><h1>✅ Akun Anda Telah Aktif!</h1></div>
                <div class='content'>
                    <p><strong>Selamat, " . htmlspecialchars($user_data->NamaLengkap) . "!</strong></p>
                    <div class='success-box'>
                        <p>Kabar baik! Akun Anda di <strong>Sapa Baru</strong> telah berhasil diverifikasi oleh admin dan sekarang sudah aktif. Anda dapat mulai menggunakan sistem kami dengan login menggunakan NIK dan password sementara yang telah kami kirimkan pada email sebelumnya.</p>
                    </div>
                    <div style='text-align: center; margin: 30px 0;'>
                        <p>Silakan login ke sistem untuk mulai menggunakan layanan Sapa Baru.</p>
                    </div>
                </div>
                <div class='footer'><p>© " . date('Y') . " Sapa Baru</p></div>
            </div>
        </body></html>";
        
        $this->email->message($message);
        if (!$this->email->send()) {
            log_message('error', 'Email verifikasi tidak dapat dikirim: ' . $this->email->print_debugger());
        }
    }

    private function send_rejection_email($user_data, $alasan)
    {
        $config = ['protocol' => 'smtp', 'smtp_host' => 'smtp.gmail.com', 'smtp_user' => 'dodokss113@gmail.com', 'smtp_pass' => 'jhckccmzmiokuknz', 'smtp_port' => 587, 'smtp_crypto' => 'tls', 'mailtype' => 'html', 'charset' => 'utf-8', 'newline' => "\r\n"];
        $this->email->initialize($config);
        $this->email->from('dodoks113@gmail.com', 'Sapa Baru');
        $this->email->to($user_data->Email);
        $this->email->subject('Pemberitahuan Status Pendaftaran - Sapa Baru');
        
        $message = "
        <html><head><title>Pemberitahuan Status Pendaftaran</title>
        <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f8fafc; }
            .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
            .header { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 30px 20px; text-align: center; }
            .header h1 { margin: 0; font-size: 28px; }
            .content { padding: 30px; color: #374151; }
            .rejection-box { background: #fef2f2; padding: 20px; border-radius: 8px; border-left: 4px solid #ef4444; margin: 20px 0; }
            .reason-box { background: #fafafa; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #e5e7eb; }
            .reason-box h4 { margin-top: 0; color: #1f2937; }
            .footer { text-align: center; color: #6b7280; margin-top: 30px; padding: 20px; font-size: 13px; background: #f9fafb; border-top: 1px solid #e5e7eb; }
        </style>
        </head><body>
            <div class='container'>
                <div class='header'><h1>📋 Status Pendaftaran Ditolak</h1></div>
                <div class='content'>
                    <p><strong>Kepada Yth. " . htmlspecialchars($user_data->NamaLengkap) . ",</strong></p>
                    <div class='rejection-box'>
                        <p>Mohon maaf, setelah melakukan review, pendaftaran akun Anda di <strong>Sapa Baru</strong> belum dapat kami setujui saat ini.</p>
                    </div>
                    <div class='reason-box'>
                        <h4>📝 Alasan Penolakan:</h4>
                        <p style='font-style: italic;'>\"" . htmlspecialchars($alasan) . "\"</p>
                    </div>
                    <p>Jika ada pertanyaan, silakan hubungi admin.</p>
                </div>
                <div class='footer'><p>© " . date('Y') . " Sapa Baru</p></div>
            </div>
        </body></html>";
        
        $this->email->message($message);
        if (!$this->email->send()) {
            log_message('error', 'Email penolakan tidak dapat dikirim: ' . $this->email->print_debugger());
        }
    }
}