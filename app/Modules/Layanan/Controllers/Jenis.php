<?php

namespace Modules\Layanan\Controllers;

use App\Controllers\BaseController;
use Modules\Layanan\Models\JenisModel;
use Ramsey\Uuid\Uuid;

class Jenis extends BaseController
{
    protected $folder_directory = "Modules\\Layanan\\Views\\jenis\\";
    private $indukmodule = 'Tata Kelola Layanan';
    private $subindukmodule = '';
    private $title = 'Jenis';
    private $module = 'layanan/jenis';

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
        $model = new JenisModel();

        // Mendapatkan parameter dari DataTables
        $limit = $request->getPost('length'); // Limit untuk jumlah data yang ditampilkan
        $start = $request->getPost('start'); // Offset data
        $orderColumnIndex = $request->getPost('order')[0]['column']; // Indeks kolom untuk pengurutan
        $orderDirection = $request->getPost('order')[0]['dir']; // Arah pengurutan (ASC/DESC)
        $search = $request->getPost('search')['value']; // Nilai pencarian

        $columns = [
            'a.lay_id',
            'a.lay_nama',
            'a.lay_urutan',
            'a.lay_status',
            'a.lay_update',
        ];
        $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'lay_id';

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
            $count++;
            $row = [
                'no' => $count,
                'lay_nama' => $val->lay_nama,
                'lay_urutan' => $val->lay_urutan,
                'lay_update' => $val->lay_update,
                // 'jab_nama' => wordwrap($jab_nama, 60, "<br>", false),
                'lay_status' => $val->lay_status == 1 ? '<span class="badge bg-success">Aktif</span>':'<span class="badge bg-danger">Tidak Aktif</span>',
                'aksi' => '
                    <a class="btn btn-warning btn-sm waves-effect waves-light" href="' . base_url('layanan/jenis/'.$val->lay_id.'/edit') .'">
                        <i data-lucide="edit"></i> Edit
                    </a>
                    <button class="btn btn-danger btn-sm waves-effect waves-light btn-delete" data-id="' . $val->lay_id . '">
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
        $model = new JenisModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        // Validasi form
        $validation->setRules([
            'lay_nama' => 'required',
            'lay_urutan' => 'required',
            'lay_status' => 'required',
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
            'lay_id' => $uuid,
            'lay_nama' => $this->request->getPost('lay_nama'),
            'lay_urutan' => $this->request->getPost('lay_urutan'),
            'lay_status' => $this->request->getPost('lay_status'),
            'lay_update' => gmdate('Y-m-d H:i:s', time() + 25200),
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

    function edit($lay_id)
    {
        $model = new JenisModel();
        $data['lay_id'] = $lay_id;
        $data['layanan'] = $model->data_id($lay_id);
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Edit';
        $data['view'] = $this->folder_directory .'edit';
        return view('layout/admin/templates', $data);
    }

    function update($lay_id)
    {
        $model = new JenisModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        // Validasi form
        $validation->setRules([
            'lay_nama' => 'required',
            'lay_urutan' => 'required',
            'lay_status' => 'required',
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
            'lay_nama' => $this->request->getPost('lay_nama'),
            'lay_urutan' => $this->request->getPost('lay_urutan'),
            'lay_status' => $this->request->getPost('lay_status'),
            'lay_update' => gmdate('Y-m-d H:i:s', time() + 25200),
        ];

        try {
            if ($model->edit($lay_id, $data)) {
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
        $model = new JenisModel();
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
            if ($model->hapus($kirimID)) 
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
        } catch (\Exception $e) {

            return $this->response->setJSON([
                csrf_token() => $token,
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ]);
        }
    }
}