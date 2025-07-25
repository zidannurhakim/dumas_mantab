<?php

namespace Modules\Manajemen\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'x_user';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
    {
        $builder = $this->db->table('x_user usr');

        // Pencarian (search)
        if ($search) {
            $builder->groupStart()
                ->like('usr.usr_email', $search)
                ->orLike('usr.usr_full', $search)
                ->orLike('usr.usr_update', $search)
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
        $builder = $this->db->table('x_user usr');

        // Pencarian (search)
        if ($search) {
            $builder->groupStart()
                    ->like('usr.usr_email', $search)
                    ->orLike('usr.usr_full', $search)
                    ->orLike('usr.usr_update', $search)
                    ->groupEnd();
        }

        return $builder->countAllResults();
    }

    function countAllData()
    {
        // Menghitung total data tanpa filter
        $builder = $this->db->table('x_user usr');
        return $builder->countAllResults();
    }

    function tambah($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_user');
        return $builder->insert($data);
    }

    function data_id($id)
    {
        $query = $this->db->query('SELECT * FROM x_user AS usr WHERE usr.usr_id = ?', array($id));
        return $query->getResult();
    }

    function edit($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_user');
        $builder->where('usr_id', $id);
        return $builder->update($data);
    }

    function hapus($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_user');
        $builder->where('usr_id', $id);
        return $builder->delete();
    }
}