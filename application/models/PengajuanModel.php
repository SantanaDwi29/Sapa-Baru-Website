<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PengajuanModel extends CI_Model {

    public function getAllPengajuan() {
        $this->db->select('tb_pengajuan.*, tb_pendatang.NamaLengkap, tb_keperluan.NamaKeperluan');
        $this->db->from('tb_pengajuan');
        $this->db->join('tb_pendatang', 'tb_pengajuan.idPendatang = tb_pendatang.idPendatang');
        $this->db->join('tb_keperluan', 'tb_pengajuan.idKeperluan = tb_keperluan.idKeperluan');
        $this->db->order_by('tb_pengajuan.TanggalPengajuan', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getPengajuanById($idPengajuan) {
        $this->db->select('*'); 
        $this->db->from('tb_pengajuan');
        $this->db->join('tb_pendatang', 'tb_pengajuan.idPendatang = tb_pendatang.idPendatang');
        $this->db->join('tb_keperluan', 'tb_pengajuan.idKeperluan = tb_keperluan.idKeperluan');
        $this->db->where('tb_pengajuan.idPengajuan', $idPengajuan);
        return $this->db->get()->row_array();
    }

    public function insertPengajuan($data) {
        $data['TanggalPengajuan'] = date('Y-m-d H:i:s');
        $data['StatusPengajuan'] = 'Pending';
        $data['NomorSurat'] = '-';
        return $this->db->insert('tb_pengajuan', $data);
    }

    public function updatePengajuan($idPengajuan, $data) {
        $this->db->where('idPengajuan', $idPengajuan);
        return $this->db->update('tb_pengajuan', $data);
    }

    public function deletePengajuan($idPengajuan) {
        $this->db->where('idPengajuan', $idPengajuan);
        return $this->db->delete('tb_pengajuan');
    }
    public function cekNomorSuratAda($nomorSurat)
{
    $this->db->where('NomorSurat', $nomorSurat);
    $query = $this->db->get('tb_pengajuan');

    if ($query->num_rows() > 0) {
        return true; 
    } else {
        return false;
    }
}
}