<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogModel extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_riwayat_pengajuan()
    {

        $this->db->select('
            pengajuan.NomorSurat,
            pengajuan.TanggalPengajuan,
            pengajuan.StatusPengajuan,
            pengajuan.Alasan,
            pendatang.NamaLengkap as nama_pendatang,
            pendatang.NIK as nik_pendatang,
            keperluan.NamaKeperluan as nama_keperluan
        ');

        $this->db->from('tb_pengajuan as pengajuan');

               $this->db->join('tb_pendatang as pendatang', 'pengajuan.idPendatang = pendatang.idPendatang', 'left');
        $this->db->join('tb_keperluan as keperluan', 'pengajuan.idKeperluan = keperluan.idKeperluan', 'left');

        $this->db->order_by('pengajuan.TanggalPengajuan', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * CONTOH FUNGSI LAIN DI MASA DEPAN
     * Anda bisa menambahkan fungsi log lain di sini jika dibutuhkan.
     * Misalnya, untuk melihat riwayat login pengguna.
     *
     * public function get_riwayat_login($limit = 50)
     * {
     * $this->db->from('tb_login_attempts'); // Asumsi ada tabel untuk mencatat upaya login
     * $this->db->order_by('attempt_time', 'DESC');
     * $this->db->limit($limit);
     * return $this->db->get()->result();
     * }
     */
}