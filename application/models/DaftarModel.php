<?php
class DaftarModel extends CI_Model
{
    private $table = 'tb_daftar';

    public function get_all()
    {
        $this->db->select('idDaftar,NIK,NamaLengkap,Alamat,Telp,Email,JenisAkun,Password,StatusAktivasi,Alasan,FotoProfil');
        $this->db->order_by('NIK', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['idDaftar' => $id])->row();
    }

    public function get_by_nik($nik)
    {
        $this->db->select('*, latitude_daftar, longitude_daftar');
        return $this->db->get_where($this->table, ['NIK' => $nik])->row();
    }

    public function update_by_nik($nik, $data)
    {
        return $this->db->update($this->table, $data, ['NIK' => $nik]);
    }
    public function updatePhoto($nik, $filename)
    {
        $this->db->where('NIK', $nik);
        return $this->db->update($this->table, ['FotoProfil' => $filename]);
    }

    public function deletePhoto($nik)
    {
        $this->db->where('NIK', $nik);
        return $this->db->update($this->table, ['FotoProfil' => null]);
    }


    public function getPhotoFilename($nik)
    {
        $this->db->select('FotoProfil');
        $this->db->where('NIK', $nik);
        $result = $this->db->get($this->table)->row();
        return $result ? $result->FotoProfil : null;
    }


    public function is_email_exists($email, $current_nik = null)
    {
        $this->db->where('Email', $email);
        if ($current_nik) {
            $this->db->where('NIK !=', $current_nik);
        }
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }

    public function verify_login($nik, $password)
    {
        $this->db->where('NIK', $nik);
        $this->db->where('StatusAktivasi', 'Aktif');
        $user = $this->db->get($this->table)->row();

        if ($user && password_verify($password, $user->Password)) {
            return $user;
        }
        return false;
    }

    public function get_verified_users()
    {
        $this->db->select('idDaftar,NIK,NamaLengkap,Alamat,Telp,Email,JenisAkun,StatusAktivasi');
        $this->db->where('StatusAktivasi', 'Aktif');
        $this->db->order_by('NamaLengkap', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->update($this->table, $data, ['idDaftar' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['idDaftar' => $id]);
    }

    public function get_all_kaling()
    {
        $this->db->select('idDaftar as idKaling, NamaLengkap as NamaKaling, NIK, Telp, Email, JenisAkun, StatusAktivasi as StatusAkun, Alamat');
        $this->db->from($this->table);
        $this->db->where('JenisAkun', 'Kepala Lingkungan');
        $this->db->where('StatusAktivasi', 'Aktif');
        return $this->db->get()->result();
    }

    public function get_all_pj()
    {
        $this->db->select('idDaftar as idPJ, NamaLengkap as NamaPJ, NIK, Telp, Email, JenisAkun, StatusAktivasi as StatusAkun, Alamat, Latitude_daftar, Longitude_daftar');
        $this->db->from($this->table);
        $this->db->where('JenisAkun', 'Penanggung Jawab');
        $this->db->where('StatusAktivasi', 'Aktif');
        return $this->db->get()->result();
    }

    public function change_password($nik, $new_password)
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $this->db->where('NIK', $nik);
        return $this->db->update($this->table, ['Password' => $hashed_password]);
    }
    public function getTotalPJ()
    {
        $this->db->where('JenisAkun', 'Penanggung Jawab');
        $this->db->where('StatusAktivasi', 'Aktif');
        return $this->db->count_all_results($this->table);
    }
    public function getTotalKaling()
    {
        $this->db->where('JenisAkun', 'Kepala Lingkungan');
        $this->db->where('StatusAktivasi', 'Aktif');
        return $this->db->count_all_results($this->table);
    }
    public function getTotalPendaftarBaru()
    {
        $this->db->where('StatusAktivasi', 'Pending');
        return $this->db->count_all_results($this->table);
    }
    public function get_pj_by_id($id_pj)
    {
        $this->db->select('idDaftar, NamaLengkap, Latitude_daftar, Longitude_daftar');
        $this->db->from('tb_daftar');
        $this->db->where('idDaftar', $id_pj);
        $query = $this->db->get();
        return $query->row();
    }
    public function count_by_role($role)
    {
        return $this->db->where('JenisAkun', $role)->from('tb_daftar')->count_all_results();
    }
    public function count_by_status($status)
    {
        return $this->db->where('StatusAktivasi', $status)->from('tb_daftar')->count_all_results();
    }
    public function get_unverified_users()
    {
        $this->db->select('d.idDaftar, d.NIK, d.NamaLengkap, d.Telp, d.JenisAkun, d.StatusAktivasi, d.Alasan');
        $this->db->from('tb_daftar d');

        $this->db->join('tb_login l', 'd.NIK = l.NIK', 'left');

        $this->db->where('l.idLogin IS NULL');

        $this->db->where_in('d.StatusAktivasi', ['Pending', 'Ditolak']);

        $this->db->order_by('d.idDaftar', 'DESC');
        return $this->db->get()->result();
    }
}
