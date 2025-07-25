<?php

namespace Modules\Manajemen\Controllers;

use App\Controllers\BaseController;
use Modules\Manajemen\Models\LeveluserModel;
use Ramsey\Uuid\Uuid;

class Leveluser extends BaseController
{
    protected $folder_directory = "Modules\\Manajemen\\Views\\leveluser\\";
    private $indukmodule = 'Manajemen';
    private $subindukmodule = '';
    private $title = 'Level User';
    private $module = 'manajemen/level-user';
    
    function __construct()
    {
        helper('auth');
        is_logged();
        is_allowed($this->module);
    }
    
    function index()
    {
        // print_r(session()->privmod);
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
        $model = new LeveluserModel();

        // Mendapatkan parameter dari DataTables
        $limit = $request->getPost('length'); // Limit untuk jumlah data yang ditampilkan
        $start = $request->getPost('start'); // Offset data
        $orderColumnIndex = $request->getPost('order')[0]['column']; // Indeks kolom untuk pengurutan
        $orderDirection = $request->getPost('order')[0]['dir']; // Arah pengurutan (ASC/DESC)
        $search = $request->getPost('search')['value']; // Nilai pencarian

        // Menentukan kolom untuk pengurutan
        $columns = ['usg_id', 'usg_name', 'usg_note'];
        $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'usg_id';

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
                'usg_name' => $val->usg_name,
                'usg_note' => $val->usg_note,
                'aksi' => '
                    <a class="btn btn-success btn-sm waves-effect waves-light" href="' . base_url('manajemen/level-user/'.$val->usg_id.'/module') .'"><i data-lucide="settings"></i> Module</a>
                    <a class="btn btn-warning btn-sm waves-effect waves-light" href="' . base_url('manajemen/level-user/'.$val->usg_id.'/edit') .'">
                        <i data-lucide="edit"></i>  Edit
                    </a>
                    <a class="btn btn-danger btn-sm waves-effect waves-light" href="' . base_url('manajemen/level-user/'.$val->usg_id.'/hapus') .'">
                        <i data-lucide="trash"></i>  Hapus
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
        // Generate UUID for usg_id
        $uuid = Uuid::uuid4()->toString();
        $model = new LeveluserModel();
        
        // Prepare data for insertion
        $data = [
            'usg_id' => $uuid,
            'usg_name' => $this->request->getPost('usg_name'),
            'usg_note' => $this->request->getPost('usg_note'),
        ];
        
        // Insert data into main table
        if ($model->tambah($data)) {
            // Prepare data for privmod table using the same UUID
            $data_privmod = [
                'usg_id' => $uuid,
                'mod_id' => '1', // Default Mod_id Beranda
            ];
            
            // Insert default privilege for the new user group
            $model->tambah_privmod($data_privmod);
            
            // Set success flash data
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Berhasil Menambahkan Data.',
                    'icon' => 'success'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            return redirect()->to('/manajemen/level-user');
        } else {
            // Set error flash data
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Gagal Menambahkan Data.',
                    'icon' => 'error'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            return redirect()->back();
        }
    }


    function edit($id)
    {
        $model = new LeveluserModel();
        $data['level'] = $model->data_id($id);
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Edit';
        $data['view'] = $this->folder_directory .'edit';
        return view('layout/admin/templates', $data);
    }

    function update($id)
    {
        $model = new LeveluserModel();
        $data = array(
            'usg_name' => $this->request->getPost('usg_name'),
            'usg_note' => $this->request->getPost('usg_note'),
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
        return redirect()->to('manajemen/level-user');
    }

    function hapus($id)
    {
        if (session()->usg_name == 'Superadmin') 
        {
            $model = new LeveluserModel();
            if($model->hapus($id))
            {
                $model->hapus_privmod($id); //Hapus di privmod juga
                $model->hapus_userrole($id);
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
        }else {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Anda tidak punya akses!',
                    'icon' => 'error',
                ],
            ];
        }
        session()->setFlashdata($sessFlashdata);
        return redirect()->to('manajemen/level-user');
    }

    function module($id)
    {
        $model = new LeveluserModel();
        $data['usg_id'] = $id;
        $data['grup_list'] = $model->gruplist();
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Module';
        $data['view'] = $this->folder_directory .'module';
        return view('layout/admin/templates', $data);
    }

    function module_ajax()
    {
        $model = new LeveluserModel();
        $usg = $this->request->getPost('usg');
        
        // DataTables parameters
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];

        // Ambil data mod_list dan privmod_list
        $mod_list = $model->mod_list($start, $length, $search);
        $privmod_list = $model->privmod_list($usg);
        $privs = array_column($privmod_list, 'mod_id');
        
        $data = [];
        foreach ($mod_list as $val) {
            if (in_array($val->mod_id, $privs, true)) {
                $status = "<span class='badge bg-success'>True</span>";
                $combox = "<input type='checkbox' class='privmod_upd' id='".$val->mod_id."' data-ops='del' checked>";
            } else {
                $status = "<span class='badge bg-warning'>False</span>";
                $combox = "<input type='checkbox' class='privmod_upd' id='".$val->mod_id."' data-ops='add'>";
            }

            $induk = empty($val->parent) ? "-" : $val->parent;

            $data[] = [
                $induk,
                $val->mod_name,
                "<center>".$status."</center>",
                "<center>".$combox."</center>"
            ];
        }

        // Count total records and filtered records
        $totalRecords = $model->countAllModList();
        $filteredRecords = $model->countFilteredModList($search);

        $token = csrf_hash();

        $result = [
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
            'csrfHash' => $token
        ];

        return $this->response->setJSON($result);
    }

    function privmod_upd() 
    {
        $model = new LeveluserModel();
		$ops = $this->request->getPost('ops');
		$usg = $this->request->getPost('usg');
		$mod = $this->request->getPost('mod');

		if($ops == "add") $upd = $model->privmod_add($usg,$mod);
		else $upd = $model->privmod_del($usg,$mod);

		if($upd) echo "success";
		else echo "error";
	}

}