<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_profile_data')) {
    function get_profile_data() {
        $CI =& get_instance();
        $CI->load->model('DaftarModel');
        
        $nik_user = $CI->session->userdata('NIK');
        
        if (!$nik_user) {
            return [
                'hasProfilePhoto' => false,
                'FotoProfil' => base_url('assets/img/default-profile.jpg')
            ];
        }
        
        $profile = $CI->DaftarModel->get_by_nik($nik_user);
        
        if ($profile && !empty($profile->FotoProfil) && file_exists(FCPATH . 'uploads/profile/' . $profile->FotoProfil)) {
            return [
                'hasProfilePhoto' => true,
                'FotoProfil' => base_url('uploads/profile/' . $profile->FotoProfil)
            ];
        }
        
        return [
            'hasProfilePhoto' => false,
            'FotoProfil' => base_url('assets/img/default-profile.jpg')
        ];
    }
}

if (!function_exists('generate_random_filename')) {
    function generate_random_filename($extension = 'jpg') {
        $characters = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        return substr(str_shuffle($characters), 0, 8) . '_' . time() . '.' . $extension;
    }
}