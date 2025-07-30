<?php

namespace Modules\Modules\Models;

use CodeIgniter\Model;

class LayITModel extends Model
{
    protected $table = 'layanan_data';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
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

        $builder->orderBy($orderColumn, $orderDirection);

        $builder->limit($limit, $start);

        return $builder->get()->getResult();
    }

    function countFiltered($search = null)
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

        return $builder->countAllResults();
    }


    function countAll()
    {
        $builder = $this->db->table('layanan_data AS a');
        $builder->select('a.*, b.lay_nama');
        $builder->join('layanan_jenis AS b', 'a.lay_id = b.lay_id', 'left');
        $builder->orderBy('a.data_update', 'DESC');
        return $builder->countAllResults();
    }

    function data_id($id)
    {
        $query = $this->db->query('SELECT a.*, b.lay_nama FROM layanan_data AS a 
        LEFT JOIN layanan_jenis AS b ON a.lay_id = b.lay_id
        WHERE a.data_id = ?', array($id));
        return $query->getResult();
    }
}