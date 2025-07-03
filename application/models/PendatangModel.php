<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PendatangModel extends CI_Model {
    private $table = 'tb_pendatang';

    public function get_all_pendatang() {
        $this->db->order_by('idPendatang', 'DESC');
        $query = $this->db->get($this->table);
        return $query->result(); 
    }

    public function get_foto_by_id($id) {
        $this->db->select('FotoDiri, FotoKTP');
        $this->db->where('idPendatang', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['idPendatang' => $id])->row();
    }

    // // Method baru untuk mendapatkan detail lengkap pendatang untuk view modal
    // public function get_detail_by_id($id) {
    //     $this->db->select('p.*, k.NamaKaling');
    //     $this->db->from($this->table . ' p');
    //     $this->db->join('tb_kaling k', 'p.idKaling = k.idKaling', 'left');
    //     $this->db->where('p.idPendatang', $id);
    //     return $this->db->get()->row();
    // }

    public function insert($data) {
        // Hapus idPendatang jika kosong untuk auto increment
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
        $this->db->from('pendatang');
        
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
        $this->db->from('pendatang');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('NamaLengkap', $search);
            $this->db->or_like('NIK', $search);
            $this->db->or_like('Alasan', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
    public function get_available_pendatang() {
        $this->db->select('p.*');
        $this->db->from('tb_pendatang p');
        // Join conditions to filter out pendatang with 'Pending' or 'Terverifikasi' applications
        $this->db->join('tb_pengajuan pj', 'p.idPendatang = pj.idPendatang AND (pj.StatusPengajuan = "Pending" OR pj.StatusPengajuan = "Terverifikasi")', 'left');
        $this->db->where('pj.idPengajuan IS NULL'); // Only include if there's NO matching active application
        // If you want to allow re-application after rejection, you'd add:
        // $this->db->or_where('pj.StatusPengajuan', 'Ditolak');
        $this->db->group_by('p.idPendatang'); // Important to prevent duplicates if a pendatang has multiple rejected applications

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
        
        // Filter untuk data arsip
        $this->db->where_in('p.StatusTinggal', ['Tidak Aktif', 'Pindah']); // Sesuaikan value status arsip

        // Filter per Kaling jika diperlukan
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
public function getTotalPendatangAktif()
{
    $this->db->where('StatusTinggal', 'Aktif');
    return $this->db->count_all_results($this->table); //
}
public function get_bulan_stats() {
    $this->db->select("
        MONTH(TanggalMasuk) as bulan,
        COUNT(idPendatang) as jumlah
    ");
    $this->db->from($this->table);
    $this->db->where('YEAR(TanggalMasuk)', date('Y')); // Ambil data untuk tahun ini
    $this->db->group_by('bulan');
    $this->db->order_by('bulan', 'ASC');

    $query = $this->db->get();
    return $query->result_array();
}
public function get_tahun_stats() {
    $this->db->select("
        YEAR(TanggalMasuk) as tahun,
        COUNT(idPendatang) as jumlah
    ");
    $this->db->from($this->table);
    $this->db->group_by('tahun');
    $this->db->order_by('tahun', 'ASC');

    $query = $this->db->get();
    return $query->result_array();
}
}