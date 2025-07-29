<?php

namespace Modules\Landing\Models;

use CodeIgniter\Model;

class PortalModel extends Model
{
    protected $table = 'layanan_jenis';

    function data_jenislayanan_select2($search = null, $limit = 50)
    {
        $builder = $this->db->table('layanan_jenis AS a')
            ->select('a.*')
            ->orderBy('a.lay_urutan', 'ASC')
            ->limit($limit);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('a.lay_nama', $search)
                    ->groupEnd();
        }

        return $builder->get()->getResult();
    }

    function tambah($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('layanan_data');
        return $builder->insert($data);
    }
}