<?php

namespace Modules\Modules\Models;

use CodeIgniter\Model;

class PesanMasukModel extends Model
{
    protected $table = 'layanan_data';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
    {
        $usr_id = session()->usr_id;
        $builder = $this->db->table('layanan_data_log AS a');
        $builder->select('
            b.data_id,
            c.lay_nama,
            b.data_metode,
            b.data_nama,
            b.data_nip,
            b.data_peran,
            b.data_email,
            b.data_nomorhp,
            b.data_subjek,
            a.log_status_baca,
            u.usr_full AS log_usr_full_pengirim,
            u.usr_nip AS log_usr_nip_pengirim,
            u.usr_email AS log_usr_email_pengirim,
            u.usr_nomorhp AS log_usr_nomorhp_pengirim,
            a.log_pesan,
            b.data_status_kirim_update, 
            b.data_update
        ');
        $builder->join('layanan_data AS b', 'a.data_id = b.data_id', 'left');
        $builder->join('layanan_jenis AS c', 'b.lay_id = c.lay_id', 'left');
        $builder->join('x_user AS u', 'a.log_usr_id_pengirim = u.usr_id', 'left');
        $builder->where('b.data_status_kirim', "KIRIM");
        $builder->where('b.data_status_selesai', "BELUM");
        $builder->where('a.log_usr_id_tujuan', $usr_id);
        $builder->orderBy('b.data_update', 'DESC');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('c.lay_nama', $search)
                    ->orLike('b.data_metode', $search)
                    ->orLike('b.data_nama', $search)
                    ->orLike('b.data_nip', $search)
                    ->orLike('b.data_peran', $search)
                    ->orLike('b.data_email', $search)
                    ->orLike('b.data_nomorhp', $search)
                    ->orLike('b.data_subjek', $search)
                    ->orLike('u.usr_full', $search)
                    ->orLike('u.usr_nip', $search)
                    ->orLike('u.usr_email', $search)
                    ->orLike('u.usr_nomorhp', $search)
                    ->orLike('a.log_pesan', $search)
                    ->orLike('b.data_status_kirim_update', $search)
                    ->orLike('b.data_update', $search)
                    ->groupEnd();
        }

        if (!empty($orderColumn) && !empty($orderDirection)) {
            $builder->orderBy($orderColumn, $orderDirection);
        }
        $builder->limit($limit, $start);
        return $builder->get()->getResult();
    }

    function countFiltered($search)
    {
        $builder = $this->db->table('layanan_data_log AS a');
        $builder->select('
            b.data_id,
            c.lay_nama,
            b.data_metode,
            b.data_nama,
            b.data_nip,
            b.data_peran,
            b.data_email,
            b.data_nomorhp,
            b.data_subjek,
            a.log_status_baca,
            u.usr_full AS log_usr_full_pengirim,
            u.usr_nip AS log_usr_nip_pengirim,
            u.usr_email AS log_usr_email_pengirim,
            u.usr_nomorhp AS log_usr_nomorhp_pengirim,
            a.log_pesan,
            b.data_status_kirim_update,
            b.data_update
        ');
        $builder->join('layanan_data AS b', 'a.data_id = b.data_id', 'left');
        $builder->join('layanan_jenis AS c', 'b.lay_id = c.lay_id', 'left');
        $builder->join('x_user AS u', 'a.log_usr_id_pengirim = u.usr_id', 'left');
        $builder->where('b.data_status_kirim', "KIRIM");
        $builder->where('b.data_status_selesai', "BELUM");
        $builder->orderBy('b.data_update', 'DESC');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('c.lay_nama', $search)
                    ->orLike('b.data_metode', $search)
                    ->orLike('b.data_nama', $search)
                    ->orLike('b.data_nip', $search)
                    ->orLike('b.data_peran', $search)
                    ->orLike('b.data_email', $search)
                    ->orLike('b.data_nomorhp', $search)
                    ->orLike('b.data_subjek', $search)
                    ->orLike('u.usr_full', $search)
                    ->orLike('u.usr_nip', $search)
                    ->orLike('u.usr_email', $search)
                    ->orLike('u.usr_nomorhp', $search)
                    ->orLike('a.log_pesan', $search)
                    ->orLike('b.data_status_kirim_update', $search)
                    ->orLike('b.data_update', $search)
                    ->groupEnd();
        }
        return $builder->countAllResults();
    }


    function countAll()
    {
        $builder = $this->db->table('layanan_data_log AS a');
        $builder->select('
            b.data_id,
            c.lay_nama,
            b.data_metode,
            b.data_nama,
            b.data_nip,
            b.data_peran,
            b.data_email,
            b.data_nomorhp,
            b.data_subjek,
            a.log_status_baca,
            u.usr_full AS log_usr_full_pengirim,
            u.usr_nip AS log_usr_nip_pengirim,
            u.usr_email AS log_usr_email_pengirim,
            u.usr_nomorhp AS log_usr_nomorhp_pengirim,
            a.log_pesan,
            b.data_status_kirim_update,
            b.data_update
        ');
        $builder->join('layanan_data AS b', 'a.data_id = b.data_id', 'left');
        $builder->join('layanan_jenis AS c', 'b.lay_id = c.lay_id', 'left');
        $builder->join('x_user AS u', 'a.log_usr_id_pengirim = u.usr_id', 'left');
        $builder->where('b.data_status_kirim', "KIRIM");
        $builder->where('b.data_status_selesai', "BELUM");
        $builder->orderBy('b.data_update', 'DESC');
        return $builder->countAllResults();
    }
}