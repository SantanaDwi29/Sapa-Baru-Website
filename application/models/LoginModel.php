<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class LoginModel extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function cekPendaftarNIK($NIK) {
        $this->db->where('NIK', $NIK);
        return $this->db->get('tb_daftar')->row();
    }
    
    public function cekLoginNIK($NIK) {
        $this->db->where('NIK', $NIK);
        $query = $this->db->get('tb_login');
        return ($query->num_rows() > 0) ? $query->row() : null;
    }
    
    public function isAdmin($NIK) {
        $this->db->where('NIK', $NIK);
        $this->db->where('JenisAkun', 'Admin');
        $query = $this->db->get('tb_login');
        return ($query->num_rows() > 0);
    }

    public function insertToLogin($data) {
        return $this->db->insert('tb_login', $data);
    }
    
    public function get_by_nik($nik) {
        return $this->cekLoginNIK($nik);
    }
    
    public function deleteFromLogin($NIK) {
        $this->db->where('NIK', $NIK);
        return $this->db->delete('tb_login');
    }
    public function updateLogin($nik, $data)
    {
        $this->db->where('NIK', $nik);
        return $this->db->update('tb_login', $data);
    }

    public function is_email_exists($email, $current_nik = null) {
        $this->db->where('Email', $email);
        if($current_nik) {
            $this->db->where('NIK !=', $current_nik);
        }
        $query = $this->db->get('tb_login');
        return $query->num_rows() > 0;
    }
    

    public function get_all_active_users() {
        $this->db->select('NIK, NamaLengkap, Alamat, Telp, Email, JenisAkun');
        $this->db->order_by('NamaLengkap', 'ASC');
        return $this->db->get('tb_login')->result();
    }

    public function get_by_account_type($jenis_akun) {
        $this->db->where('JenisAkun', $jenis_akun);
        $this->db->order_by('NamaLengkap', 'ASC');
        return $this->db->get('tb_login')->result();
    }

    public function verify_login($nik, $password) {
        $this->db->where('NIK', $nik);
        $user = $this->db->get('tb_login')->row();
        
        if($user && password_verify($password, $user->Password)) {
            return $user;
        }
        return false;
    }

    // public function get_profile($nik) {
    //     $this->db->select('NIK, NamaLengkap, Alamat, Telp, Email, JenisAkun');
    //     $this->db->where('NIK', $nik);
    //     return $this->db->get('tb_login')->row();
    // }
    

    // public function update_profile($nik, $data) {
    //     // Remove NIK from data to prevent updating primary key
    //     unset($data['NIK']);
        
    //     $this->db->where('NIK', $nik);
    //     return $this->db->update('tb_login', $data);
    // }

    public function change_password($nik, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $this->db->where('NIK', $nik);
        return $this->db->update('tb_login', ['Password' => $hashed_password]);
    }
    

    public function get_by_email($email) {
        $this->db->where('Email', $email);
        return $this->db->get('tb_login')->row();
    }

    public function user_exists($nik) {
        $this->db->where('NIK', $nik);
        $query = $this->db->get('tb_login');
        return $query->num_rows() > 0;
    }
    // public function updatePhoto($nik, $filename) {
    //     $this->db->where('NIK', $nik);
    //     return $this->db->update('tb_login', ['FotoProfil' => $filename]);
    // }
    
    // public function deletePhoto($nik) {
    //     $this->db->where('NIK', $nik);
    //     return $this->db->update('tb_login', ['FotoProfil' => null]);
    // }
    
    // public function getPhotoFilename($nik) {
    //     $this->db->select('FotoProfil');
    //     $this->db->where('NIK', $nik);
    //     $result = $this->db->get('tb_login')->row();
        
    //     return $result ? $result->FotoProfil : null;
    // }
}
?>