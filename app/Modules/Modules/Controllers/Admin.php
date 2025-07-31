<?php

namespace Modules\Modules\Controllers;

use App\Controllers\BaseController;
use Modules\Modules\Models\AdminModel;
use Ramsey\Uuid\Uuid;

class Admin extends BaseController
{
    protected $folder_directory = "Modules\\Modules\\Views\\admin\\";
    private $indukmodule = 'Modules';
    private $subindukmodule = 'Layanan';
    private $title = 'Panel Admin';
    private $module = 'module/admin';

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
        $model = new AdminModel();

        $limit = $request->getPost('length'); 
        $start = $request->getPost('start'); 
        $orderColumnIndex = $request->getPost('order')[0]['column']; 
        $orderDirection = $request->getPost('order')[0]['dir'];
        $search = $request->getPost('search')['value'];
        $lay_id = $request->getPost('lay_id');

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

        $totalData = $model->countAll($lay_id);
        $totalFiltered = $totalData;

        if (empty($search)) {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection, null, $lay_id);
        } else {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection, $search, $lay_id);
            $totalFiltered = $model->countFiltered($search, $lay_id);
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
                'data_status_kirim' => '<span class="badge bg-dark">'.$val->data_status_kirim.'</span>',
                'data_status_selesai' => '<span class="badge bg-primary">'.$val->data_status_selesai.'</span>',
                'data_update' => $this->tanggal_indo_datetime($val->data_update, true),
                'aksi' => '
                    <a class="btn btn-success btn-sm waves-effect waves-light" href="' . base_url('module/admin/'.$val->data_id.'/detail') .'">
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
        $model = new AdminModel();
        $data['data_id'] = $id;
        $data['data'] = $model->data_id($id);
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Detail Data';
        $data['view'] = $this->folder_directory .'detail';
        return view('layout/admin/templates', $data);
    }

    function proses_kirim($data_id)
    {
        $model = new AdminModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();
        $uuid = Uuid::uuid4()->toString();

        // Validasi form
        $validation->setRules([
            'usr_id' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                csrf_token() => $token,
                'status' => 'error',
                'message' => 'Lengkapi Kembali Data dan Pastikan Tidak Ada Yang Kosong.',
                'errors' => $validation->getErrors(),
            ]);
        }

        $data = [
            'log_id' => $uuid,
            'data_id' => $data_id,
            'log_usr_id_pengirim' => session()->usr_id,
            'log_usr_id_tujuan' => $this->request->getPost('usr_id'),
            'log_tujuan_jenis' => "KIRIM",
            'log_hal' => "MENERUSKAN DATA",
            'log_pesan' => "Segera Ditindaklanjuti",
            'log_lampiran' => null,
            'log_status_baca' => "BELUM",
            'log_flag_tindaklanjut' => "BELUM",
            'log_update' => gmdate('Y-m-d H:i:s', time() + 25200)
        ];

        $update_data = [
            'data_status_kirim' => "KIRIM",
            'data_status_kirim_update' => gmdate('Y-m-d H:i:s', time() + 25200),
        ];

        try {
            if ($model->tambah($data)) {
                $model->edit_data($update_data, $data_id);
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'success',
                    'message' => 'Data berhasil dikirim.'
                ]);
            } else {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data ke database.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                csrf_token() => $token,
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ]);
        }
    }

    function data_layanan()
    {
        $search = $this->request->getPost('search');
        $model = new AdminModel();
        $usg_id = session()->usg_id;
        $result = $model->data_layanan_select2($search, 100, $usg_id);

        $data = [];
        foreach($result AS $val) 
        {
            $nama = $val->lay_nama;
            $data[] = [
                'id' => $val->lay_id,
                'text' => $nama
            ];
        }
        return $this->response->setJSON([
            'results' => $data
        ]);
    }

    function data_user()
    {
        $search = $this->request->getPost('search');
        $model = new AdminModel();
        $result = $model->data_user_select2($search);

        $data = [];
        foreach($result AS $val) 
        {
            $nama = $val->usr_full.' (NIP : '.$val->usr_nip.')';
            $data[] = [
                'id' => $val->usr_id,
                'text' => $nama
            ];
        }
        return $this->response->setJSON([
            'results' => $data,
            csrf_token() => csrf_hash()
        ]);
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