<?php

namespace Modules\Layanan\Controllers;

use App\Controllers\BaseController;
use Modules\Layanan\Models\AksesAdminModel;
use Ramsey\Uuid\Uuid;

class AksesAdmin extends BaseController
{
    protected $folder_directory = "Modules\\Layanan\\Views\\aksesadmin\\";
    private $indukmodule = 'Tata Kelola Layanan';
    private $subindukmodule = '';
    private $title = 'Akses Admin';
    private $module = 'layanan/unit';

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
        $model = new AksesAdminModel();

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
                    <a class="btn btn-success btn-sm waves-effect waves-light" href="' . base_url('layanan/akses-admin/'.$val->usg_id.'/module') .'"><i data-lucide="settings"></i> Module</a>'

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

    function module($id)
    {
        $model = new AksesAdminModel();
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
        $model = new AksesAdminModel();
        $usg = $this->request->getPost('usg');
        
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];

        $mod_list = $model->mod_list($start, $length, $search);
        $privmod_list = $model->privmod_list($usg);
        $privs = array_column($privmod_list, 'lay_id');
        
        $data = [];
        foreach ($mod_list as $val) {
            if (in_array($val->lay_id, $privs, true)) {
                $status = "<span class='badge bg-success'>True</span>";
                $combox = "<input type='checkbox' class='privmod_upd' id='".$val->lay_id."' data-ops='del' checked>";
            } else {
                $status = "<span class='badge bg-warning'>False</span>";
                $combox = "<input type='checkbox' class='privmod_upd' id='".$val->lay_id."' data-ops='add'>";
            }

            $data[] = [
                $val->lay_nama,
                "<center>".$status."</center>",
                "<center>".$combox."</center>"
            ];
        }

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
        $model = new AksesAdminModel();
		$ops = $this->request->getPost('ops');
		$usg = $this->request->getPost('usg');
		$lay_id = $this->request->getPost('lay_id');

		if($ops == "add") $upd = $model->privmod_add($usg,$lay_id);
		else $upd = $model->privmod_del($usg,$lay_id);

		if($upd) echo "success";
		else echo "error";
	}

}