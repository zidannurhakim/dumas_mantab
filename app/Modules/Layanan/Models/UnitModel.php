<?php

namespace Modules\Layanan\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table = 'layanan_unit';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
    {
        $builder = $this->db->table('layanan_unit AS a'); // Alias 'j' untuk jabatan
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
        $builder = $this->db->table('layanan_unit AS a'); 

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
        $builder = $this->db->table('layanan_unit AS a'); 
        return $builder->countAllResults();
    }

    function data_unit_select2($search = null, $limit = 50)
    {
        $builder = $this->db->table('layanan_unit AS a')
            ->select('a.*')
            ->orderBy('a.unit_nama', 'ASC')
            ->limit($limit);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('a.unit_nama', $search)
                    ->groupEnd();
        }

        return $builder->get()->getResult();
    }

    function data_id($unit_id)
    {
        $query = $this->db->query('SELECT * FROM layanan_unit AS a WHERE a.unit_id = ?', array($unit_id));
        return $query->getResult();
    }

    function tambah($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_unit');
        return $builder->insert($data);
    }

    function edit($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_unit');
        $builder->where('unit_id', $id);
        return $builder->update($data);
    }

    function hapus($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_unit');
        $builder->where('unit_id', $id);
        return $builder->delete();
    }
}