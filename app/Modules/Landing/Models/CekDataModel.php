<?php

namespace Modules\Landing\Models;

use CodeIgniter\Model;

class CekDataModel extends Model
{

    function cek_data($data_id, $data_email, $data_nip)
    {
        $query = $this->db->query("SELECT b.data_id, 
        c.lay_nama, 
        b.data_metode, 
        b.data_nama, 
        b.data_nip, 
        b.data_peran, 
        b.data_email, 
        b.data_nomorhp, 
        b.data_subjek, 
        b.data_status_kirim_update
        FROM layanan_data AS b
        LEFT JOIN layanan_jenis AS c ON b.lay_id = c.lay_id
        WHERE b.data_id = ? AND b.data_email = ? AND b.data_nip = ?
        ORDER BY b.data_update DESC", array($data_id, $data_email, $data_nip));
        return $query->getResult();
    }

    function data_id($id)
    {
        $query = $this->db->query('SELECT a.*, b.lay_nama FROM layanan_data AS a 
        LEFT JOIN layanan_jenis AS b ON a.lay_id = b.lay_id
        WHERE a.data_id = ?', array($id));
        return $query->getResult();
    }

    function data_id_rating($id)
    {
        $query = $this->db->query('SELECT a.*, b.lay_nama FROM layanan_data AS a 
        LEFT JOIN layanan_jenis AS b ON a.lay_id = b.lay_id
        WHERE a.data_id = ?', array($id));
        return $query->getResult();
    }

    function data_obrolan($data_id)
    {
        $query = $this->db->query('SELECT a.* FROM layanan_data_chat AS a 
        WHERE a.data_id = ?
        ORDER BY a.chat_update DESC', array($data_id));
        return $query->getResult();
    }

    function tambah_chat($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_data_chat');
        return $builder->insert($data);
    }
    
    function edit_data($data, $data_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_data');
        $builder->where('data_id', $data_id);
        return $builder->update($data);
    }
}