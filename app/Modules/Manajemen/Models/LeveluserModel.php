<?php

namespace Modules\Manajemen\Models;

use CodeIgniter\Model;

class LeveluserModel extends Model
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

    function tambah($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_usergroup');
        return $builder->insert($data);
    }

    function tambah_privmod($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_privmod');
        return $builder->insert($data);
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

    function edit($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_usergroup');
        $builder->where('usg_id', $id);
        return $builder->update($data);
    }

    function hapus($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_usergroup');
        $builder->where('usg_id', $id);
        return $builder->delete();
    }

    function hapus_privmod($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_privmod');
        $builder->where('usg_id', $id);
        return $builder->delete();
    }

    function hapus_userrole($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_userrole');
        $builder->where('usg_id', $id);
        return $builder->delete();
    }

    function mod_list($start, $length, $search)
    {
        $builder = $this->db->table('x_module a');
        $builder->select('a.mod_id, a.mod_name, b.mod_name AS parent');
        $builder->join('x_module b', 'a.mod_parent = b.mod_id', 'left');
        $builder->where('a.mod_id <>', 1);
        $builder->where('a.mod_display', 'Y');

        // Apply search filter if provided
        if (!empty($search)) {
            $builder->like('a.mod_name', $search);
            $builder->orLike('b.mod_name', $search);
        }

        // Limit the records for pagination
        $builder->limit($length, $start);
        $query = $builder->get();

        return $query->getResult();
    }

    function countAllModList()
    {
        $builder = $this->db->table('x_module a');
        $builder->selectCount('a.mod_id');
        $builder->where('a.mod_id <>', 1);
        $builder->where('a.mod_display', 'Y');
        $query = $builder->get();

        return $query->getRow()->mod_id;
    }

    function countFilteredModList($search)
    {
        $builder = $this->db->table('x_module a');
        $builder->selectCount('a.mod_id');
        $builder->join('x_module b', 'a.mod_parent = b.mod_id', 'left');
        $builder->where('a.mod_id <>', 1);
        $builder->where('a.mod_display', 'Y');

        if (!empty($search)) {
            $builder->like('a.mod_name', $search);
            $builder->orLike('b.mod_name', $search);
        }

        $query = $builder->get();
        return $query->getRow()->mod_id;
    }


    function privmod_list($usg_id) {
        $query = $this->db->query("SELECT * FROM x_privmod WHERE usg_id = ?",array($usg_id));
        
        return $query->getResult();
    }

    function privmod_add($usg_id, $mod_id)
    {
        $data = [
            'usg_id' => $usg_id,
            'mod_id' => $mod_id
        ];

        return $this->db->table('x_privmod')->insert($data);
    }

    function privmod_del($usg_id, $mod_id)
    {
        return $this->db->table('x_privmod')
                        ->where('usg_id', $usg_id)
                        ->where('mod_id', $mod_id)
                        ->delete();
    }

}