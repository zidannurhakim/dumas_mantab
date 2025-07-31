<?php

namespace Modules\Layanan\Models;

use CodeIgniter\Model;

class AksesAdminModel extends Model
{
    protected $table = 'x_usergroup';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
    {
        $builder = $this->db->table('x_usergroup usg');
        $builder->orderBy('usg.usg_name', 'asc');

        // Pencarian (search)
        if ($search) {
            $builder->groupStart()
                ->like('usg.usg_name', $search)
                ->orLike('usg.usg_note', $search)
                ->groupEnd();
        }

        // Pengurutan (order)
        $builder->orderBy($orderColumn, $orderDirection);

        // Limit dan Offset (pagination)
        $builder->limit($limit, $start);

        return $builder->get()->getResult();
    }

    function countFiltered($search = null)
    {
        $builder = $this->db->table('x_usergroup usg');
        $builder->orderBy('usg.usg_name', 'asc');

        // Pencarian (search)
        if ($search) {
            $builder->groupStart()
                    ->like('usg.usg_name', $search)
                    ->orLike('usg.usg_note', $search)
                    ->groupEnd();
        }

        return $builder->countAllResults();
    }

    function countAllData()
    {
        // Menghitung total data tanpa filter
        $builder = $this->db->table('x_usergroup usg');
        $builder->orderBy('usg.usg_name', 'asc');
        return $builder->countAllResults();
    }

    function data_id($id)
    {
        $query = $this->db->query('SELECT * FROM x_usergroup AS usg WHERE usg.usg_id = ?', array($id));
        return $query->getResult();
    }

    function gruplist()
    {
        $query = $this->db->query('SELECT * FROM x_usergroup AS usg');
        return $query->getResult();
    }


    function mod_list($start, $length, $search)
    {
        $builder = $this->db->table('layanan_jenis a');
        $builder->select('a.*');
        $builder->where('a.lay_status', '1');
        $builder->orderBy('a.lay_urutan', 'ASC');

        if (!empty($search)) {
            $builder->like('a.lay_nama', $search);
        }

        $builder->limit($length, $start);
        $query = $builder->get();

        return $query->getResult();
    }

    function countAllModList()
    {
        $builder = $this->db->table('layanan_jenis a');
        $builder->select('a.*');
        $builder->where('a.lay_status', '1');
        $builder->orderBy('a.lay_urutan', 'ASC');
        $query = $builder->get();

        return $builder->countAllResults();
    }

    function countFilteredModList($search)
    {
        $builder = $this->db->table('layanan_jenis a');
        $builder->select('a.*');
        $builder->where('a.lay_status', '1');
        $builder->orderBy('a.lay_urutan', 'ASC');

        if (!empty($search)) {
            $builder->like('a.lay_nama', $search);
        }

        $query = $builder->get();
        return $builder->countAllResults();
    }


    function privmod_list($usg_id) {
        $query = $this->db->query("SELECT * FROM layanan_jenis_privmod WHERE usg_id = ?",array($usg_id));
        
        return $query->getResult();
    }

    function privmod_add($usg_id, $lay_id)
    {
        $data = [
            'usg_id' => $usg_id,
            'lay_id' => $lay_id
        ];

        return $this->db->table('layanan_jenis_privmod')->insert($data);
    }

    function privmod_del($usg_id, $lay_id)
    {
        return $this->db->table('layanan_jenis_privmod')
                        ->where('usg_id', $usg_id)
                        ->where('lay_id', $lay_id)
                        ->delete();
    }

}