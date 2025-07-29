<?php

namespace Modules\Landing\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    function cekUser($usr_email)
    {
        $builder = $this->db->table('x_user');
        $builder->select('x_user.*, x_userrole.*, x_usergroup.*');
        $builder->join('x_userrole', 'x_userrole.usr_id=x_user.usr_id');;
        $builder->join('x_usergroup', 'x_userrole.usg_id=x_usergroup.usg_id');
        $builder->where('x_user.usr_email', $usr_email);
        $query = $builder->get();

        return $query->getResult();
    }

    function usgmod()
    {
        // user role //
        $r1 = $this->usgrole();
        if (empty($r1)) $role = 0;
        else {
            $role = "";
            foreach ($r1 as $val) {
                $role .= $val->usg_id . ",";
            }
            $role = substr($role, 0, -1);
        }
        // dd($role); 
        // privilege modul //
        $q2 = $this->db->query("SELECT DISTINCT b.* FROM x_privmod a 
                LEFT JOIN x_module b ON a.mod_id=b.mod_id 
                WHERE a.usg_id = ? ORDER BY b.mod_order", array($role));

        $r2 = $q2->getResult();

        return $r2;
    }

    function usgrole()
    {
        $query = $this->db->query("SELECT b.* FROM x_userrole a 
            LEFT JOIN x_usergroup b ON a.usg_id=b.usg_id 
            WHERE a.usr_id=?", array(session()->usr_id));
        return $query->getResult();
    }

    function modparent()
    {
        $query = $this->db->query("SELECT * FROM x_module WHERE mod_parent=0 ORDER BY mod_order ASC");
        return $query->getResult();
    }

    function modchild($parent)
    {
        $query = $this->db->query(
            "SELECT * FROM x_module WHERE mod_parent=? ORDER BY mod_order ASC",
            array($parent)
        );
        return $query->getResult();
    }
}
