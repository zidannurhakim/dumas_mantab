<?php

namespace Modules\Manajemen\Models;

use CodeIgniter\Model;

class HakaksesModel extends Model
{
    protected $table = 'x_userrole';

    function data($limit, $start, $orderColumn, $orderDirection, $search = null)
    {
        $builder = $this->db->table('x_userrole usrl');
        $builder->select('usr.usr_id, usr.usr_full, usr.usr_email, usg.usg_name, usrl.update');
        $builder->join('x_user usr', 'usrl.usr_id = usr.usr_id');
        $builder->join('x_usergroup usg', 'usrl.usg_id = usg.usg_id');

        // Pencarian (search)
        if ($search) {
            $builder->groupStart()
                ->like('usr.usr_full', $search)
                ->orLike('usr.usr_email', $search)
                ->orLike('usg.usg_name', $search)
                ->orLike('usrl.update', $search)
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
        $builder = $this->db->table('x_userrole usrl');
        $builder->select('usr.usr_id, usr.usr_full, usr.usr_email, usg.usg_name, usrl.update');
        $builder->join('x_user usr', 'usrl.usr_id = usr.usr_id');
        $builder->join('x_usergroup usg', 'usrl.usg_id = usg.usg_id');

        // Pencarian (search)
        if ($search) {
            $builder->groupStart()
                    ->like('usr.usr_email', $search)
                    ->orLike('usr.usr_full', $search)
                    ->orLike('usg.usg_name', $search)
                    ->orLike('usrl.update', $search)
                    ->groupEnd();
        }

        return $builder->countAllResults();
    }

    function countAllData()
    {
        // Menghitung total data tanpa filter
        $builder = $this->db->table('x_userrole usrl');
        $builder->select('usr.usr_id, usr.usr_full, usr.usr_email, usg.usg_name, usrl.update');
        $builder->join('x_user usr', 'usrl.usr_id = usr.usr_id');
        $builder->join('x_usergroup usg', 'usrl.usg_id = usg.usg_id');
        return $builder->countAllResults();
    }

    function data_user_all()
    {
        $query = $this->db->query("SELECT * FROM x_user WHERE x_user.usr_active = 'Y'");
        return $query->getResult();
    }
    
    function data_user()
    {
        $query = $this->db->query("SELECT x_user.usr_id, x_user.usr_email, x_user.usr_full
        FROM x_user
        LEFT JOIN x_userrole ON x_user.usr_id = x_userrole.usr_id
        WHERE x_userrole.usr_id IS NULL AND x_user.usr_active = 'Y'");
        return $query->getResult();
    }

    function data_id($id)
    {
        $query = $this->db->query("SELECT a.*, b.usr_full, b.usr_email
        FROM x_userrole AS a 
        JOIN x_user AS b ON a.usr_id = b.usr_id
        WHERE a.usr_id = ?", array($id));
        return $query->getResult();
    }

    function data_level()
    {
        $query = $this->db->query("SELECT * FROM x_usergroup AS usg");
        return $query->getResult();
    }

    function x_module()
    {
        $query = $this->db->query("SELECT * FROM x_module");
        return $query->getResult();
    }

    function tambah($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_userrole');
        return $builder->insert($data);
    }

    function edit($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_userrole');
        $builder->where('usr_id', $id);
        return $builder->update($data);
    }

    function hapus($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('x_userrole');
        $builder->where('usr_id', $id);
        return $builder->delete();
    }
}