<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PengajuanModel extends CI_Model {

     public function getAllPengajuan($id_pj = null) {
        $this->db->select('
            tp.idPengajuan,
            tp.NomorSurat,
            tp.TanggalPengajuan,
            tp.StatusPengajuan,
            tp.Alasan,
            tpen.NamaLengkap,
            tpen.idPenanggungJawab,
            tkep.NamaKeperluan
        ');
        $this->db->from('tb_pengajuan as tp');
        $this->db->join('tb_pendatang as tpen', 'tp.idPendatang = tpen.idPendatang', 'left');
        $this->db->join('tb_keperluan as tkep', 'tp.idKeperluan = tkep.idKeperluan', 'left');

        // Ini adalah logika filter yang ditambahkan
        if ($id_pj !== null) {
            $this->db->where('tpen.idPenanggungJawab', $id_pj);
        }

        $this->db->order_by('tp.TanggalPengajuan', 'DESC');
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
public function get_total_surat_by_role($jenis_akun, $id_user = null)
{
    $this->db->from('tb_pengajuan');

    if ($jenis_akun === 'Penanggung Jawab') {
        if ($id_user !== null) {
            $this->db->join('tb_pendatang', 'tb_pengajuan.idPendatang = tb_pendatang.idPendatang');
            
            $this->db->where('tb_pendatang.idPenanggungJawab', $id_user);
        }
    } elseif ($jenis_akun === 'Kepala Lingkungan') {
        $this->db->where('StatusPengajuan', 'Pending');
    }

    return $this->db->count_all_results();
}
}