<?php

namespace Modules\Beranda\Models;

use CodeIgniter\Model;

class BerandaModel extends Model
{
    function data_total()
    {
        $query = $this->db->query("SELECT 
        (SELECT COUNT(*) FROM pa_naskahmasuk WHERE nm_delete = 'TIDAK') AS 'Naskah',
        (SELECT COUNT(*) FROM pa_tl_naskahmasuk WHERE tl_jenis = 'DISPOSISI') AS 'Disposisi',
        (SELECT COUNT(*) FROM pa_tl_naskahmasuk WHERE tl_jenis = 'TEMBUSAN') AS 'Tembusan',
        (
            (SELECT COUNT(*) FROM pa_naskahmasuk WHERE nm_delete = 'TIDAK') +
            (SELECT COUNT(*) FROM pa_tl_naskahmasuk WHERE tl_jenis = 'DISPOSISI') +
            (SELECT COUNT(*) FROM pa_tl_naskahmasuk WHERE tl_jenis = 'TEMBUSAN')
        ) AS 'Total'");
        return $query->getResult();
    }

    function data_naskahmasuk($usr_id)
    {
        $query = $this->db->query("SELECT 
            COUNT(CASE WHEN e.log_statusbaca = 'BELUM' THEN 1 END) AS total_baca,
            COUNT(CASE WHEN e.log_flagtindaklanjut = 'BELUM' THEN 1 END) AS total_tindaklanjut
        FROM pa_naskahmasuk a
        JOIN konfig_tujuannaskah b ON a.tn_id = b.tn_id
        JOIN x_user c ON b.usr_id = c.usr_id
        JOIN mst_subsatker d ON b.s_id = d.s_id
        JOIN pa_log_naskahmasuk e ON a.nm_id = e.nm_id
        WHERE e.log_jenis = 'KIRIM'
        AND b.usr_id = ?
        AND a.nm_status_kirim = 'KIRIM'
        AND a.nm_delete = 'TIDAK'
        AND a.nm_arsip = 'BELUM'", array($usr_id));
        return $query->getResult();
    }

    function data_tembusan($usr_id)
    {
        $query = $this->db->query("SELECT 
            COUNT(CASE WHEN g.log_statusbaca = 'BELUM' THEN 1 END) AS total_baca,
            COUNT(CASE WHEN g.log_flagtindaklanjut = 'BELUM' THEN 1 END) AS total_tindaklanjut
        FROM pa_tl_naskahmasuk AS a
        JOIN konfig_tujuannaskah AS b ON a.tn_id = b.tn_id
        JOIN x_user AS c ON b.usr_id = c.usr_id
        JOIN mst_jabatan AS d ON b.jab_id = d.jab_id
        JOIN mst_subsatker AS e ON b.s_id = e.s_id
        JOIN pa_naskahmasuk AS f ON a.nm_id = f.nm_id
        JOIN pa_log_naskahmasuk AS g ON a.nm_id = g.nm_id
        JOIN konfig_instruksi AS h ON a.ins_id = h.ins_id
        WHERE a.tl_jenis = 'TEMBUSAN'AND c.usr_id = ? AND g.log_tn_id = b.tn_id", array($usr_id));
        return $query->getResult();
    }

    function data_disposisi($usr_id)
    {
        $query = $this->db->query("SELECT 
            COUNT(CASE WHEN g.log_statusbaca = 'BELUM' THEN 1 END) AS total_baca,
            COUNT(CASE WHEN g.log_flagtindaklanjut = 'BELUM' THEN 1 END) AS total_tindaklanjut
        FROM pa_tl_naskahmasuk AS a
        JOIN konfig_tujuannaskah AS b ON a.tn_id = b.tn_id
        JOIN x_user AS c ON b.usr_id = c.usr_id
        JOIN mst_jabatan AS d ON b.jab_id = d.jab_id
        JOIN mst_subsatker AS e ON b.s_id = e.s_id
        JOIN pa_naskahmasuk AS f ON a.nm_id = f.nm_id
        JOIN pa_log_naskahmasuk AS g ON a.nm_id = g.nm_id
        JOIN konfig_instruksi AS h ON a.ins_id = h.ins_id
        WHERE a.tl_jenis = 'DISPOSISI'AND c.usr_id = ? AND g.log_tn_id = b.tn_id", array($usr_id));
        return $query->getResult();
    }
}