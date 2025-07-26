<?php

namespace Modules\Layanan\Controllers;

use App\Controllers\BaseController;
use Modules\Layanan\Models\UnitModel;
use Ramsey\Uuid\Uuid;

class Unit extends BaseController
{
    protected $folder_directory = "Modules\\Layanan\\Views\\unit\\";
    private $indukmodule = 'Tata Kelola Layanan';
    private $subindukmodule = '';
    private $title = 'Unit';

    function __construct()
    {
        helper('auth');
        is_logged();
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
        $model = new UnitModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        // Validasi form
        $validation->setRules([
            'unit_nama' => 'required',
            'unit_unitid' => 'required',
            'unit_status' => 'required',
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
            'unit_id' => $uuid,
            'unit_nama' => $this->request->getPost('unit_nama'),
            'unit_unitid' => $this->request->getPost('unit_unitid'),
            'unit_status' => $this->request->getPost('unit_status'),
            'unit_update' => gmdate('Y-m-d H:i:s', time() + 25200),
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

    function data_unit()
    {
        $search = $this->request->getPost('search');
        $model = new UnitModel();
        $result = $model->data_unit_select2($search);

        $data = [];
        foreach($result AS $val) 
        {
            if($val->unit_unitid == "KOSONG")
            {
                $unit_nama = $val->unit_nama;
            }else
            {
                $getInduk = $model->data_id($val->unit_unitid);
                if($getInduk[0]->unit_unitid == "KOSONG")
                {
                    $unit_nama = $val->unit_nama.' | '.$getInduk[0]->unit_nama;
                }else
                {
                    $getInduk2 = $model->data_id($getInduk[0]->unit_unitid);
                    $unit_nama = $val->unit_nama. ' | ' .$getInduk[0]->unit_nama.' | '.$getInduk2[0]->unit_nama;
                }
            }
            $data[] = [
                'id' => $val->unit_id,
                'text' => $unit_nama
            ];
        }
        return $this->response->setJSON([
            'results' => $data,
            csrf_token() => csrf_hash()
        ]);
    }
}