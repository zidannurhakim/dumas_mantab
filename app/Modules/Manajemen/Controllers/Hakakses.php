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
                    <a class="btn btn-warning btn-sm waves-effect waves-light" href="' . base_url('manajemen/hak-akses/'.$val->usr_id.'/edit') .'">
                        <i data-lucide="edit"></i> Edit
                    </a>
                    <a class="btn btn-danger btn-sm waves-effect waves-light" href="' . base_url('manajemen/hak-akses/'.$val->usr_id.'/hapus') .'">
                        <i data-lucide="trash"></i> Hapus
                    </a>'

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
        $model = new HakaksesModel();
        $data['user'] = $model->data_user();
        $data['level'] = $model->data_level();
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
        $data = [
            'usr_id' => $this->request->getPost('usr_id'),
            'usg_id' => $this->request->getPost('usg_id'),
            'update' => gmdate('Y-m-d H:i:s', time() + 25200)
        ];
        $model->tambah($data);
        $sessFlashdata = [
            'sweetAlert' => [
                'message' => 'Berhasil Menambahkan Data.',
                'icon' => 'success'
            ],
        ];
        session()->setFlashdata($sessFlashdata);
        return redirect()->to('/manajemen/hak-akses');
    }

    function edit($id)
    {
        $model = new HakaksesModel();
        $data['datauser'] = $model->data_id($id);
        $data['user'] = $model->data_user_all();
        $data['level'] = $model->data_level();
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Edit';
        $data['view'] = $this->folder_directory .'edit';
        return view('layout/admin/templates', $data);
    }

    function update($id)
    {
        $model = new HakaksesModel();
        $data = array(
            'usg_id' => $this->request->getPost('usg_id'),
            'update' => gmdate('Y-m-d H:i:s', time() + 25200)
        );

        if ($model->edit($id, $data)) {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Data berhasil terupdate.',
                    'icon' => 'success'
                ],
            ];
        } else {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Data gagal diubah, mohon periksa kembali.',
                    'icon' => 'warning'
                ],
            ];
        }
        session()->setFlashdata($sessFlashdata);
        return redirect()->to('manajemen/hak-akses');
    }

    function hapus($id)
    {
        $model = new HakaksesModel();
        if($model->hapus($id))
        {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Data berhasil terhapus.',
                    'icon' => 'success'
                ],
            ];
        }else
        {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Data gagal dihapus, mohon periksa kembali.',
                    'icon' => 'warning'
                ],
            ];
        }
        session()->setFlashdata($sessFlashdata);
        return redirect()->to('manajemen/hak-akses');
    }
}