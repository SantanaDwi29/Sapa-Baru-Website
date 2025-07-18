<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PendatangModel extends CI_Model {
    private $table = 'tb_pendatang';

   public function get_all_pendatang_with_details()
    {
        $this->db->select('p.*, k.NamaLengkap as NamaKaling, pj.NamaLengkap as NamaPJ');
        $this->db->from('tb_pendatang p');
        $this->db->join('tb_daftar k', 'p.idKaling = k.idDaftar', 'left');
        $this->db->join('tb_daftar pj', 'p.idPenanggungJawab = pj.idDaftar', 'left');
        $this->db->order_by('p.TanggalMasuk', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }


public function get_pendatang_by_pj($id_pj)
{
    $this->db->select('p.*, k.NamaLengkap as NamaKaling, pj.NamaLengkap as NamaPJ');
    $this->db->from('tb_pendatang p');
    $this->db->join('tb_daftar k', 'p.idKaling = k.idDaftar', 'left');
    $this->db->join('tb_daftar pj', 'p.idPenanggungJawab = pj.idDaftar', 'left');
    $this->db->where('p.idPenanggungJawab', $id_pj);
    $this->db->order_by('p.TanggalMasuk', 'DESC');

    $query = $this->db->get();

   
    return $query->result();
}
    

    public function get_detail_by_id($id) {
        $this->db->select('p.*, k.NamaLengkap as NamaKaling, pj.NamaLengkap as NamaPJ');
        $this->db->from($this->table . ' p');
        $this->db->join('tb_daftar k', 'p.idKaling = k.idDaftar', 'left');
        $this->db->join('tb_daftar pj', 'p.idPenanggungJawab = pj.idDaftar', 'left');
        $this->db->where('p.idPendatang', $id);
        return $this->db->get()->row();
    }


    public function get_foto_by_id($id) {
        $this->db->select('FotoDiri, FotoKTP');
        $this->db->where('idPendatang', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['idPendatang' => $id])->row();
    }

    public function insert($data) {
        if (empty($data['idPendatang'])) {
            unset($data['idPendatang']);
        }
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['idPendatang' => $id]);
    }

    public function delete($id) {
        $this->db->where('idPendatang', $id);
        return $this->db->delete($this->table);
    }

    public function get_pendatang_paginated($limit, $offset, $search = '') {
        $this->db->select('*');
        $this->db->from($this->table); 
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('NamaLengkap', $search);
            $this->db->or_like('NIK', $search);
            $this->db->or_like('Alasan', $search);
            $this->db->group_end();
        }
        
        $this->db->limit($limit, $offset);
        $this->db->order_by('TanggalMasuk', 'DESC');
        return $this->db->get()->result();
    }
    
    public function count_pendatang($search = '') {
        $this->db->from($this->table);
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('NamaLengkap', $search);
            $this->db->or_like('NIK', $search);
            $this->db->or_like('Alasan', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

   public function get_available_pendatang($id_pj = null)
{
    $this->db->select('idPendatang, NamaLengkap');
    $this->db->from('tb_pendatang');
    $this->db->where('StatusTinggal', 'Aktif'); 
    if ($id_pj !== null) {
        $this->db->where('idPenanggungJawab', $id_pj);
    }

    $this->db->order_by('NamaLengkap', 'ASC');
    $query = $this->db->get();
    return $query->result();
}

    public function get_laporan_pendatang_aktif($idKaling = null) {
        $this->db->select('
            p.*,
            k.NamaLengkap as NamaKaling
        ');
        $this->db->from($this->table . ' p');
        $this->db->join('tb_daftar k', 'p.idKaling = k.idDaftar', 'left');
        
        $this->db->where('p.StatusTinggal', 'Aktif');

        if ($idKaling !== null) {
            $this->db->where('p.idKaling', $idKaling);
        }

        $this->db->order_by('p.TanggalMasuk', 'DESC');
        return $this->db->get()->result();
    }

    public function get_laporan_pendatang_arsip($idKaling = null) {
        $this->db->select('
            p.*,
            k.NamaLengkap as NamaKaling
        ');
        $this->db->from($this->table . ' p');
        $this->db->join('tb_daftar k', 'p.idKaling = k.idDaftar', 'left');
        
        $this->db->where_in('p.StatusTinggal', ['Tidak Aktif', 'Pindah']); // Sesuaikan value status arsip

        if ($idKaling !== null) {
            $this->db->where('p.idKaling', $idKaling);
        }
        
        $this->db->order_by('p.TanggalKeluar', 'DESC');
        return $this->db->get()->result();
    }

    public function get_rekap_by_lingkungan() {
        $this->db->select('k.NamaLengkap as NamaKaling, COUNT(p.idPendatang) as JumlahPendatang');
        $this->db->from($this->table . ' p');
        $this->db->join('tb_daftar k', 'p.idKaling = k.idDaftar', 'left');
        $this->db->where('p.StatusTinggal', 'Aktif');
        $this->db->group_by('p.idKaling');
        $this->db->order_by('JumlahPendatang', 'DESC');
        return $this->db->get()->result();
    }

  public function getTotalPendatangAktif($id_pj = null) {
    $this->db->from($this->table);
    $this->db->where('StatusTinggal', 'Aktif');

    if ($id_pj !== null) {
        $this->db->where('idPenanggungJawab', $id_pj);
    }
    
    return $this->db->count_all_results();
}
   public function get_bulan_stats($id_pj = null) {
    $this->db->select("MONTH(TanggalMasuk) as bulan, COUNT(idPendatang) as jumlah");
    $this->db->from($this->table);
    $this->db->where('YEAR(TanggalMasuk)', date('Y'));

    if ($id_pj !== null) {
        $this->db->where('idPenanggungJawab', $id_pj);
    }

    $this->db->group_by('bulan');
    $this->db->order_by('bulan', 'ASC');
    return $this->db->get()->result_array();
}

public function get_tahun_stats($id_pj = null) {
    $this->db->select("YEAR(TanggalMasuk) as tahun, COUNT(idPendatang) as jumlah");
    $this->db->from($this->table);

    if ($id_pj !== null) {
        $this->db->where('idPenanggungJawab', $id_pj);
    }

    $this->db->group_by('tahun');
    $this->db->order_by('tahun', 'ASC');
    return $this->db->get()->result_array();
}
public function get_pj_details($id) {
        $this->db->select('idDaftar, NamaLengkap, Latitude, Longitude, Alamat'); 
        $this->db->from('tb_daftar');
        $this->db->where('idDaftar', $id);
        return $this->db->get()->row();
    }
    public function getTotalPendatangPending()
{
    $this->db->where('StatusTinggal', 'Pending');
    return $this->db->count_all_results($this->table); 
}
    
}
