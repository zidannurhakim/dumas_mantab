<?php

namespace Modules\Modules\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'layanan_data';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null, $lay_id = null)
    {
        $builder = $this->db->table('layanan_data AS a');
        $builder->select('a.*, b.lay_nama');
        $builder->join('layanan_jenis AS b', 'a.lay_id = b.lay_id', 'left');
        $builder->orderBy('a.data_update', 'DESC');

        if (!empty($search)) {
            $builder->groupStart()
                        ->like('b.lay_nama', $search)
                        ->orLike('a.data_metode', $search)
                        ->orLike('a.data_nama', $search)
                        ->orLike('a.data_nip', $search)
                        ->orLike('a.data_peran', $search)
                        ->orLike('a.data_email', $search)
                        ->orLike('a.data_nomorhp', $search)
                        ->orLike('a.data_subjek', $search)
                        ->orLike('a.data_status_kirim', $search)
                        ->orLike('a.data_status_selesai', $search)
                        ->orLike('a.data_update', $search)
                    ->groupEnd();
        }

        if (!empty($lay_id)) {
            $builder->where('a.lay_id', $lay_id);
        }
        $builder->orderBy($orderColumn, $orderDirection);

        $builder->limit($limit, $start);

        return $builder->get()->getResult();
    }

    function countFiltered($search, $lay_id = null)
    {
        $builder = $this->db->table('layanan_data AS a');
        $builder->select('a.*, b.lay_nama');
        $builder->join('layanan_jenis AS b', 'a.lay_id = b.lay_id', 'left');
        $builder->orderBy('a.data_update', 'DESC');

        if (!empty($search)) {
            $builder->groupStart()
                        ->like('b.lay_nama', $search)
                        ->orLike('a.data_metode', $search)
                        ->orLike('a.data_nama', $search)
                        ->orLike('a.data_nip', $search)
                        ->orLike('a.data_peran', $search)
                        ->orLike('a.data_email', $search)
                        ->orLike('a.data_nomorhp', $search)
                        ->orLike('a.data_subjek', $search)
                        ->orLike('a.data_status_kirim', $search)
                        ->orLike('a.data_status_selesai', $search)
                        ->orLike('a.data_update', $search)
                    ->groupEnd();
        }
        if (!empty($lay_id)) {
            $builder->where('a.lay_id', $lay_id);
        }
        return $builder->countAllResults();
    }


    function countAll($lay_id = null)
    {
        $builder = $this->db->table('layanan_data AS a');
        $builder->select('a.*, b.lay_nama');
        $builder->join('layanan_jenis AS b', 'a.lay_id = b.lay_id', 'left');
        $builder->orderBy('a.data_update', 'DESC');
        if (!empty($lay_id)) {
            $builder->where('a.lay_id', $lay_id);
        }
        return $builder->countAllResults();
    }

    function data_id($id)
    {
        $query = $this->db->query('SELECT a.*, b.lay_nama FROM layanan_data AS a 
        LEFT JOIN layanan_jenis AS b ON a.lay_id = b.lay_id
        WHERE a.data_id = ?', array($id));
        return $query->getResult();
    }

    function data_layanan_select2($search = null, $limit = 100, $usg_id)
    {
        $builder = $this->db->table('layanan_jenis AS a')
            ->select('a.*')
            ->join('layanan_jenis_privmod AS b', 'a.lay_id = b.lay_id', 'left')
            ->where('b.usg_id', $usg_id)
            ->orderBy('lay_urutan', 'ASC')
            ->limit($limit);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('a.lay_nama', $search)
                    ->groupEnd();
        }

        return $builder->get()->getResult();
    }

    function data_user_select2($search = null, $limit = 100)
    {
        $builder = $this->db->table('x_user AS a')
            ->select('a.*, c.usg_name')
            ->join('x_userrole AS b', 'a.usr_id = b.usr_id', 'left')
            ->join('x_usergroup AS c', 'b.usg_id = c.usg_id', 'left')
            ->orderBy('a.usr_full', 'ASC')
            ->limit($limit);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('c.usg_name', $search)
                    ->orLike('a.usr_full', $search)
                    ->orLike('a.usr_nip', $search)
                    ->orLike('a.usr_email', $search)
                    ->orLike('a.usr_nomorhp', $search)
                    ->groupEnd();
        }

        return $builder->get()->getResult();
    }

    function tambah($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_data_log');
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