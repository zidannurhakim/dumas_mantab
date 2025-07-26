<?php

namespace Modules\Layanan\Models;

use CodeIgniter\Model;

class JenisModel extends Model
{
    protected $table = 'layanan_jenis';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
    {
        $builder = $this->db->table('layanan_jenis AS a'); // Alias 'j' untuk jabatan
        $builder->select('a.*');
        
        $builder->orderBy('a.lay_urutan', 'ASC');

        if (!empty($search)) {
            $builder->groupStart()
                        ->like('a.lay_nama', $search)
                        ->orLike('a.lay_urutan', $search)
                        ->orLike('a.lay_update', $search)
                    ->groupEnd();
        }

        $builder->orderBy($orderColumn, $orderDirection);

        $builder->limit($limit, $start);

        return $builder->get()->getResult();
    }

    function countFiltered($search = null)
    {
        $builder = $this->db->table('layanan_jenis AS a'); 

        if (!empty($search)) {
            $builder->groupStart()
                        ->like('a.lay_nama', $search)
                        ->orLike('a.lay_urutan', $search)
                        ->orLike('a.lay_update', $search)
                    ->groupEnd();
        }

        return $builder->countAllResults();
    }


    function countAll()
    {
        $builder = $this->db->table('layanan_jenis AS a'); 
        return $builder->countAllResults();
    }
    
    function data_id($lay_id)
    {
        $query = $this->db->query('SELECT * FROM layanan_jenis AS a WHERE a.lay_id = ?', array($lay_id));
        return $query->getResult();
    }

    function tambah($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_jenis');
        return $builder->insert($data);
    }

    function edit($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_jenis');
        $builder->where('lay_id', $id);
        return $builder->update($data);
    }

    function hapus($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_jenis');
        $builder->where('lay_id', $id);
        return $builder->delete();
    }
}