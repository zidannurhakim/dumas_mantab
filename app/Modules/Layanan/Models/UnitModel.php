<?php

namespace Modules\Layanan\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table = 'layanan_unit';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
    {
        $builder = $this->db->table('layanan_unit AS a'); 
        $builder->select(
            'a.*,
            lay1.unit_nama AS nama1,
            lay2.unit_nama AS nama2,
            lay3.unit_nama AS nama3,
            lay4.unit_nama AS nama4,
            lay5.unit_nama AS nama5,'
        );
        $builder->join('layanan_unit AS lay1', 'lay1.unit_id = a.unit_unitid AND a.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay2', 'lay2.unit_id = lay1.unit_unitid AND lay1.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay3', 'lay3.unit_id = lay2.unit_unitid AND lay2.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay4', 'lay4.unit_id = lay3.unit_unitid AND lay3.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay5', 'lay5.unit_id = lay4.unit_unitid AND lay4.unit_unitid != "KOSONG"', 'left');
        
        $builder->orderBy('a.unit_nama', 'ASC');

        if (!empty($search)) {
            $builder->groupStart()
                        ->like('a.unit_nama', $search)
                        ->orLike('a.unit_update', $search)
                        ->orLike('lay1.unit_nama', $search)
                        ->orLike('lay2.unit_nama', $search)
                        ->orLike('lay3.unit_nama', $search)
                        ->orLike('lay4.unit_nama', $search)
                        ->orLike('lay5.unit_nama', $search)
                        ->groupEnd();
        }

        $builder->orderBy($orderColumn, $orderDirection);

        $builder->limit($limit, $start);

        return $builder->get()->getResult();
    }

    function countFiltered($search = null)
    {
        $builder = $this->db->table('layanan_unit AS a'); 
        $builder->select(
            'a.*,
            lay1.unit_nama AS nama1,
            lay2.unit_nama AS nama2,
            lay3.unit_nama AS nama3,
            lay4.unit_nama AS nama4,
            lay5.unit_nama AS nama5,'
        );
        $builder->join('layanan_unit AS lay1', 'lay1.unit_id = a.unit_unitid AND a.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay2', 'lay2.unit_id = lay1.unit_unitid AND lay1.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay3', 'lay3.unit_id = lay2.unit_unitid AND lay2.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay4', 'lay4.unit_id = lay3.unit_unitid AND lay3.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay5', 'lay5.unit_id = lay4.unit_unitid AND lay4.unit_unitid != "KOSONG"', 'left');
        $builder->orderBy('a.unit_nama', 'ASC');

        if (!empty($search)) {
            $builder->groupStart()
                        ->like('a.unit_nama', $search)
                        ->orLike('a.unit_update', $search)
                        ->orLike('lay1.unit_nama', $search)
                        ->orLike('lay2.unit_nama', $search)
                        ->orLike('lay3.unit_nama', $search)
                        ->orLike('lay4.unit_nama', $search)
                        ->orLike('lay5.unit_nama', $search)
                        ->groupEnd();
        }
        return $builder->countAllResults();
    }


    function countAll()
    {
        $builder = $this->db->table('layanan_unit AS a'); 
        $builder->select(
            'a.*,
            lay1.unit_nama AS nama1,
            lay2.unit_nama AS nama2,
            lay3.unit_nama AS nama3,
            lay4.unit_nama AS nama4,
            lay5.unit_nama AS nama5,'
        );
        $builder->join('layanan_unit AS lay1', 'lay1.unit_id = a.unit_unitid AND a.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay2', 'lay2.unit_id = lay1.unit_unitid AND lay1.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay3', 'lay3.unit_id = lay2.unit_unitid AND lay2.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay4', 'lay4.unit_id = lay3.unit_unitid AND lay3.unit_unitid != "KOSONG"', 'left');
        $builder->join('layanan_unit AS lay5', 'lay5.unit_id = lay4.unit_unitid AND lay4.unit_unitid != "KOSONG"', 'left');
        $builder->orderBy('a.unit_nama', 'ASC');
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

    function data_all()
    {
        $query = $this->db->query('SELECT * FROM layanan_unit AS a ORDER BY a.unit_nama');
        return $query->getResult();
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