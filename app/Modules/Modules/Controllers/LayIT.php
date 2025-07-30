<?php

namespace Modules\Modules\Controllers;

use App\Controllers\BaseController;
use Modules\Modules\Models\LayITModel;
use Ramsey\Uuid\Uuid;

class LayIT extends BaseController
{
    protected $folder_directory = "Modules\\Modules\\Views\\it\\";
    private $indukmodule = 'Modules';
    private $subindukmodule = 'Layanan';
    private $title = 'IT';
    private $module = 'manajemen/hak-akses';

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
        $model = new LayITModel();

        $limit = $request->getPost('length'); 
        $start = $request->getPost('start'); 
        $orderColumnIndex = $request->getPost('order')[0]['column']; 
        $orderDirection = $request->getPost('order')[0]['dir'];
        $search = $request->getPost('search')['value'];

        $columns = [
            'a.data_id',
            'b.lay_nama',
            'a.data_metode',
            'a.data_nama',
            'a.data_nip',
            'a.data_peran',
            'a.data_email',
            'a.data_nomorhp',
            'a.data_subjek',
            'a.data_status_kirim',
            'a.data_status_selesai',
            'a.data_update',
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
        foreach ($result as $val) {
            $pengirim = '<b>'.$val->data_nama.'</b> <br>NIS/NIP : '.$val->data_nip.' <br>Peran : '.$val->data_peran.' <br>Email : '.$val->data_email.' <br>Nomor HP : '.$val->data_nomorhp;
            $count++;
            $row = [
                'no' => $count,
                'lay_nama' => $val->lay_nama .'<br>'. $val->data_metode,
                'data_nama' => wordwrap($pengirim, 40, "<br>", false),
                'data_subjek' => wordwrap($val->data_subjek, 20, "<br>", false),
                'data_status_kirim' => '<span class="badge bg-warning">'.$val->data_status_kirim.'</span>',
                'data_status_selesai' => '<span class="badge bg-primary">'.$val->data_status_selesai.'</span>',
                'data_update' => $this->tanggal_indo_datetime($val->data_update, true),
                'aksi' => '
                    <a class="btn btn-success btn-sm waves-effect waves-light" href="' . base_url('module/it/'.$val->data_id.'/detail') .'">
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

    function detail($id)
    {
        $model = new LayITModel();
        $data['data'] = $model->data_id($id);
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Detail Data';
        $data['view'] = $this->folder_directory .'detail';
        return view('layout/admin/templates', $data);
    }

    function tanggal_indo($tanggal, $cetak_hari = false)
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

        $split = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }
        return $tgl_indo;
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