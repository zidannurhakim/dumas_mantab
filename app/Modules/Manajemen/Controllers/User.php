<?php

namespace Modules\Manajemen\Controllers;

use App\Controllers\BaseController;
use Modules\Manajemen\Models\HakaksesModel;
use Modules\Manajemen\Models\UserModel;
use Ramsey\Uuid\Uuid;

class User extends BaseController
{
    protected $folder_directory = "Modules\\Manajemen\\Views\\user\\";
    private $indukmodule = 'Manajemen';
    private $subindukmodule = '';
    private $title = 'User';
    private $module = 'manajemen/user';
    
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
        $model = new UserModel();

        // Mendapatkan parameter dari DataTables
        $limit = $request->getPost('length'); // Limit untuk jumlah data yang ditampilkan
        $start = $request->getPost('start'); // Offset data
        $orderColumnIndex = $request->getPost('order')[0]['column']; // Indeks kolom untuk pengurutan
        $orderDirection = $request->getPost('order')[0]['dir']; // Arah pengurutan (ASC/DESC)
        $search = $request->getPost('search')['value']; // Nilai pencarian

        // Menentukan kolom untuk pengurutan
        $columns = ['usr_id', 'usr_email', 'usr_full', 'usr_active', 'usr_inputby', 'usr_update'];
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
                'usr_email' => $val->usr_email,
                'usr_full' => $val->usr_full,
                'usr_active' => $val->usr_active == 'Y' ? '<span class="badge bg-success">Aktif</span>':'<span class="badge bg-danger">Tidak Aktif</span>',
                'usr_update' => $val->usr_update,
                'aksi' => '
                    <a class="btn btn-warning btn-sm waves-effect waves-light" href="' . base_url('manajemen/user/'.$val->usr_id.'/edit') .'">
                        <i data-lucide="edit"></i> Edit
                    </a>
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
        $uuid = Uuid::uuid4()->toString();
        $model = new UserModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        // Validasi form
        $validation->setRules([
            'usr_full' => 'required',
            'usr_tanggallahir' => 'required',
            'usr_email' => 'required',
            'usr_nip' => 'required',
            'usr_kelamin' => 'required',
            'usr_nomorhp' => 'required',
            'usr_alamat' => 'required',
            'usr_active' => 'required',
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
            'usr_id' => $uuid,
            'usr_full' => $this->request->getPost('usr_full'),
            'usr_tanggallahir' => $this->request->getPost('usr_tanggallahir'),
            'usr_email' => $this->request->getPost('usr_email'),
            'usr_nip' => $this->request->getPost('usr_nip'),
            'usr_kelamin' => $this->request->getPost('usr_kelamin'),
            'usr_nomorhp' => $this->request->getPost('usr_nomorhp'),
            'usr_alamat' => $this->request->getPost('usr_alamat'),
            'usr_active' => $this->request->getPost('usr_active'),
            'usr_inputby' => session()->usr_id,
            'usr_update' => gmdate('Y-m-d H:i:s', time() + 25200)
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

    function edit($id)
    {
        $model = new UserModel();
        $data['user'] = $model->data_id($id);
        $data['usr_id'] = $id;
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Edit';
        $data['view'] = $this->folder_directory .'edit';
        return view('layout/admin/templates', $data);
    }

    function update($id)
    {
        $model = new UserModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        // Validasi form
        $validation->setRules([
            'usr_full' => 'required',
            'usr_tanggallahir' => 'required',
            'usr_email' => 'required',
            'usr_nip' => 'required',
            'usr_kelamin' => 'required',
            'usr_nomorhp' => 'required',
            'usr_alamat' => 'required',
            'usr_active' => 'required',
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
            'usr_full' => $this->request->getPost('usr_full'),
            'usr_tanggallahir' => $this->request->getPost('usr_tanggallahir'),
            'usr_email' => $this->request->getPost('usr_email'),
            'usr_nip' => $this->request->getPost('usr_nip'),
            'usr_kelamin' => $this->request->getPost('usr_kelamin'),
            'usr_nomorhp' => $this->request->getPost('usr_nomorhp'),
            'usr_alamat' => $this->request->getPost('usr_alamat'),
            'usr_active' => $this->request->getPost('usr_active'),
            'usr_inputby' => session()->usr_id,
            'usr_update' => gmdate('Y-m-d H:i:s', time() + 25200)
        ];

        try {
            if ($model->edit($id, $data)) {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'success',
                    'message' => 'Data berhasil diubah.'
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
        $model = new UserModel();
        $modelHA = new HakaksesModel();
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
                if($modelHA->hapus($kirimID)) 
                {
                    $model->hapus($kirimID);
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
}