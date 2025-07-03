<?php

class PenanggungJawab extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'DaftarModel']);
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
        $NamaLengkap = $this->input->post('namaLengkap');
        $NIK = $this->input->post('nik');
        $Alamat = $this->input->post('alamat');
        $Telp = $this->input->post('telp');
        $Email = $this->input->post('email');
        $Password = $this->input->post('password');
        $StatusAktivasi = 'Pending';
        $JenisAkun = 'Penanggung Jawab';
        if (empty($NamaLengkap) || empty($Email) || empty($Telp) || empty($JenisAkun ) || empty ($NIK) || empty($StatusAktivasi)) {
            $this->session->set_flashdata('error', 'Semua field harus diisi kecuali password saat edit');
            redirect('PenanggungJawab');
            return;
        }
        $data = [
            
            'NamaLengkap' => $NamaLengkap,
            'NIK' => $NIK,
            'Alamat' => $Alamat,
            'Telp' => $Telp,
            'Email' => $Email,
            'StatusAktivasi' => $StatusAktivasi,
            'JenisAkun' => $JenisAkun
        ];        
        
        try {
            if (empty($idDaftar)) {
                if (empty($Password)) {
                    $this->session->set_flashdata('error', 'Password harus diisi');
                    redirect('PenanggungJawab');
                    return;
                }
                $data['Password'] = $Password;
                
                $result = $this->DaftarModel->insert($data);
                if($result){
                    $this->session->set_flashdata('success','Data penanggung jawab berhasil ditambahkan');
                }else{
                    $this->session->set_flashdata('error','Gagal menambahkan data penanggung jawab');
                }
            } else {
                if (!empty($Password)) {
                    $data['Password'] = $Password;
                }
                
                $result = $this->DaftarModel->update($idDaftar, $data);
                if($result){
                    $this->session->set_flashdata('success','Data penanggung jawab berhasil diperbarui');
                }else{
                    $this->session->set_flashdata('error','Gagal memperbarui data penanggung jawab');
                }
            }
            redirect('PenanggungJawab');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('PenanggungJawab');
        }
    }
    public function get($id)
    {
        $verifikasi = $this->DaftarModel->get_by_id($id);
        if ($verifikasi) {
            echo json_encode($verifikasi);
        } else {
            show_404();
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->DaftarModel->delete($id);
            if ($result) {
                $this->session->set_flashdata('success', 'Data pengguna berhasil dihapus');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data pengguna');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        redirect('PenanggungJawab');
    }
    function logout()
    {
        $this->session->sess_destroy();
        redirect('awalPage', 'refresh');
    }
}