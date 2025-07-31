<?php

namespace Modules\Modules\Controllers;

use App\Controllers\BaseController;
use Modules\Modules\Models\PesanMasukModel;
use Ramsey\Uuid\Uuid;

class PesanMasuk extends BaseController
{
    protected $folder_directory = "Modules\\Modules\\Views\\pesanmasuk\\";
    private $indukmodule = 'Modules';
    private $subindukmodule = 'Layanan';
    private $title = 'Pesan Masuk';
    private $module = 'module/pesan-masuk';

    function __construct()
    {
        helper('auth');
        is_logged();
        is_allowed($this->module);
    }

    function index()
    {
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Index';
        $data['view'] = $this->folder_directory .'index';
        return view('layout/admin/templates', $data);
    }

    function data()
    {
        $request = \Config\Services::request();
        $model = new PesanMasukModel();

        $limit = $request->getPost('length'); 
        $start = $request->getPost('start'); 
        $orderColumnIndex = $request->getPost('order')[0]['column']; 
        $orderDirection = $request->getPost('order')[0]['dir'];
        $search = $request->getPost('search')['value'];

        $columns = [
            'b.data_id',
            'c.lay_nama',
            'b.data_metode',
            'b.data_nama',
            'b.data_nip',
            'b.data_peran',
            'b.data_email',
            'b.data_nomorhp',
            'b.data_subjek',
            'u.usr_full AS log_usr_full_pengirim',
            'u.usr_nip AS log_usr_nip_pengirim',
            'u.usr_email AS log_usr_email_pengirim',
            'u.usr_nomorhp AS log_usr_nomorhp_pengirim',
            'a.log_pesan',
            'b.data_status_kirim_update',
        ];
        $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'data_id';

        $totalData = $model->countAll();
        $totalFiltered = $totalData;

        if (empty($search)) {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection);
        } else {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection, $search);
            $totalFiltered = $model->countFiltered($search);
        }

        $data = [];
        $count = $start;
        $statusBaca = null;
        foreach ($result as $val) {
            $pengirim = '<b>'.$val->data_nama.'</b> <br>NIS/NIP : '.$val->data_nip.' <br>Peran : '.$val->data_peran.' <br>Email : '.$val->data_email.' <br>Nomor HP : '.$val->data_nomorhp;
            $data_pengirim = '<b>'.$val->log_usr_full_pengirim.'</b> <br>NIS/NIP : '.$val->log_usr_nip_pengirim.' <br>Email : '.$val->log_usr_email_pengirim.' <br>Nomor HP : '.$val->log_usr_nomorhp_pengirim.' <br> Dikirim : '.$this->tanggal_indo_datetime($val->data_status_kirim_update, true);
            $count++;
            if($val->log_status_baca == "BACA")
            {
                $statusBaca = '<span class="badge bg-primary">'.$val->log_status_baca.'</span>';
            }elseif($val->log_status_baca == "BELUM")
            {
                $statusBaca = '<span class="badge bg-danger">'.$val->log_status_baca.'</span>';
            }
            $row = [
                'no' => $count,
                'lay_nama' => $val->lay_nama .'<br>'. $val->data_metode,
                'data_nama' => wordwrap($pengirim, 40, "<br>", false),
                'data_subjek' => wordwrap($val->data_subjek, 20, "<br>", false),
                'data_pengirim' => wordwrap($data_pengirim, 45, "<br>", false),
                'data_status_baca' => $statusBaca,
                'data_update' => $this->tanggal_indo_datetime($val->data_update, true),
                'aksi' => '
                    <a class="btn btn-success btn-sm waves-effect waves-light" href="' . base_url('module/pesan-masuk/'.$val->data_id.'/detail') .'">
                        <i data-lucide="eye"></i> Lihat Data
                    </a>'

            ];
            $data[] = $row;
        }

        $token = csrf_hash();

        $json_data = [
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
            "csrfHash" => $token
        ];

        return $this->response->setJSON($json_data);
    }


    function tanggal_indo_datetime($datetime, $cetak_hari = false)
    {
        $hari = array(
            1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        if (empty($datetime)) {
            return 'Tanggal tidak tersedia';
        }

        // Pisahkan tanggal dan waktu
        $datetime_parts = explode(' ', $datetime);
        $tanggal = $datetime_parts[0];
        $waktu = isset($datetime_parts[1]) ? $datetime_parts[1] : '';

        $split = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

        $output = $tgl_indo;

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            $output = $hari[$num] . ', ' . $tgl_indo;
        }

        if (!empty($waktu)) {
            // Format waktu jadi `08:56 WIB`
            $output .= '<br>pukul ' . substr($waktu, 0, 5) . ' WIB';
        }

        return $output;
    }
}