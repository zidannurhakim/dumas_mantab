<?php

namespace Modules\Manajemen\Controllers;

use App\Controllers\BaseController;
use Modules\Manajemen\Models\HakaksesModel;

class Hakakses extends BaseController
{
    protected $folder_directory = "Modules\\Manajemen\\Views\\hakakses\\";
    private $indukmodule = 'Manajemen';
    private $subindukmodule = '';
    private $title = 'Hak Akses';
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
        $model = new HakaksesModel();

        // Mendapatkan parameter dari DataTables
        $limit = $request->getPost('length'); // Limit untuk jumlah data yang ditampilkan
        $start = $request->getPost('start'); // Offset data
        $orderColumnIndex = $request->getPost('order')[0]['column']; // Indeks kolom untuk pengurutan
        $orderDirection = $request->getPost('order')[0]['dir']; // Arah pengurutan (ASC/DESC)
        $search = $request->getPost('search')['value']; // Nilai pencarian

        // Menentukan kolom untuk pengurutan
        $columns = ['usr_id', 'usr_full', 'usr_email', 'update'];
        $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'usr_id';

        // Mendapatkan total data tanpa filter
        $totalData = $model->countAll();
        $totalFiltered = $totalData;

        // Mengambil data dengan pencarian dan pengurutan
        if (empty($search)) {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection);
        } else {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection, $search);
            $totalFiltered = $model->countFiltered($search);
        }

        // Menyiapkan data untuk DataTables
        $data = [];
        $count = $start;
        foreach ($result as $val) {
            $count++;
            $row = [
                'no' => $count,
                'usr_full' => $val->usr_full,
                'usr_email' => $val->usr_email,
                'usg_name' => $val->usg_name,
                'update' => $val->update,
                'aksi' => '
                    <button class="btn btn-danger btn-sm waves-effect waves-light btn-delete" data-id="' . $val->usr_id . '">
                        <i data-lucide="trash"></i> Hapus
                    </button>'

            ];
            $data[] = $row;
        }

        $token = csrf_hash();

        // Menyiapkan response JSON untuk DataTables
        $json_data = [
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
            "csrfHash" => $token
        ];

        return $this->response->setJSON($json_data);
    }

    function tambah()
    {
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Tambah';
        $data['view'] = $this->folder_directory .'tambah';
        return view('layout/admin/templates', $data);
    }

    function prosestambah()
    {
        $model = new HakaksesModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        // Validasi form
        $validation->setRules([
            'usr_id' => 'required',
            'usg_id' => 'required',
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
            'usr_id' => $this->request->getPost('usr_id'),
            'usg_id' => $this->request->getPost('usg_id'),
            'update' => gmdate('Y-m-d H:i:s', time() + 25200)
        ];

        try {
            if ($model->tambah($data)) {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'success',
                    'message' => 'Data berhasil ditambahkan.'
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
    
    function hapus()
    {
        $model = new HakaksesModel();
        $kirimID = $this->request->getPost('kirimID');
        $token = csrf_hash();

        if (empty($kirimID)) {
            return $this->response->setJSON([
                csrf_token() => $token,
                'status' => 'error',
                'message' => 'ID data tidak ditemukan.'
            ]);
        }

        try {
            if(session()->usg_name == 'Superadmin') 
            {
                if($model->hapus($kirimID)) 
                {
                    return $this->response->setJSON([
                        csrf_token() => $token,
                        'status' => 'success',
                        'message' => 'Data berhasil dihapus.'
                    ]);
                } else 
                {
                    return $this->response->setJSON([
                        csrf_token() => $token,
                        'status' => 'error',
                        'message' => 'Gagal menghapus data dari database.'
                    ]);
                }
            }else
            {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'error',
                    'message' => 'Mohon maaf anda bukan Superadmin.'
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

    function data_user()
    {
        $search = $this->request->getPost('search');
        $model = new HakaksesModel();
        $result = $model->data_user_select2($search);

        $data = [];
        foreach($result AS $val) 
        {
            $nama = $val->usr_full.' | '.$val->usr_email;
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

    function data_level()
    {
        $search = $this->request->getPost('search');
        $model = new HakaksesModel();
        $result = $model->data_level_select2($search);

        $data = [];
        foreach($result AS $val) 
        {
            $nama = $val->usg_name.' | '.$val->usg_note;
            $data[] = [
                'id' => $val->usg_id,
                'text' => $nama
            ];
        }
        return $this->response->setJSON([
            'results' => $data,
            csrf_token() => csrf_hash()
        ]);
    }
}