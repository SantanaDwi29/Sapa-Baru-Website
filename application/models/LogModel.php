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

}