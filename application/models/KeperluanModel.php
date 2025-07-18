<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KeperluanModel extends CI_Model {

    public function getAllKeperluan() {
        $query = $this->db->get('tb_keperluan');
        return $query->result();
    }

    public function getKeperluanById($idKeperluan) {
        $this->db->where('idKeperluan', $idKeperluan);
        return $this->db->get('tb_keperluan')->row(); 
    }

    public function insertKeperluan($data) {
        return $this->db->insert('tb_keperluan', $data);
    }

    public function updateKeperluan($idKeperluan, $data) {
        $this->db->where('idKeperluan', $idKeperluan);
        return $this->db->update('tb_keperluan', $data);
    }

    public function deleteKeperluan($idKeperluan) {
        $this->db->where('idKeperluan', $idKeperluan);
        return $this->db->delete('tb_keperluan');
    }
}