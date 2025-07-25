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
                'usr_active' => $val->usr_active == 'Y' ? 'Aktif':'Tidak Aktif',
                'usr_update' => $val->usr_update,
                'aksi' => '
                    <a class="btn btn-warning btn-sm waves-effect waves-light" href="' . base_url('manajemen/user/'.$val->usr_id.'/edit') .'">
                        <i data-lucide="edit"></i> Edit
                    </a>
                    <a class="btn btn-danger btn-sm waves-effect waves-light" href="' . base_url('manajemen/user/'.$val->usr_id.'/hapus') .'">
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
        $data = [
            'usr_id' => $uuid,
            'usr_email' => $this->request->getPost('usr_email'),
            'usr_full' => $this->request->getPost('usr_full'),
            'usr_active' => $this->request->getPost('usr_active'),
            'usr_inputby' => session()->usr_id,
            'usr_update' => gmdate('Y-m-d H:i:s', time() + 25200)
        ];
        $model->tambah($data);
        $sessFlashdata = [
            'sweetAlert' => [
                'message' => 'Berhasil Menambahkan Data.',
                'icon' => 'success'
            ],
        ];
        session()->setFlashdata($sessFlashdata);
        return redirect()->to('/manajemen/user');
    }

    function edit($id)
    {
        $model = new UserModel();
        $data['user'] = $model->data_id($id);
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
        $data = array(
            'usr_email' => $this->request->getPost('usr_email'),
            'usr_full' => $this->request->getPost('usr_full'),
            'usr_active' => $this->request->getPost('usr_active'),
            'usr_inputby' => session()->usr_id,
            'usr_update' => gmdate('Y-m-d H:i:s', time() + 25200)
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
        return redirect()->to('manajemen/user');
    }

    function hapus($id)
    {
        $model = new UserModel();
        $modelHA = new HakaksesModel();
        if($modelHA->hapus($id))
        {
            if($model->hapus($id))
            {
                $sessFlashdata = [
                    'sweetAlert' => [
                        'message' => 'Data berhasil terhapus.',
                        'icon' => 'success'
                    ],
                ];
            }
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
        return redirect()->to('manajemen/user');
    }
}