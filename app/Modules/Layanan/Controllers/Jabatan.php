<?php

namespace Modules\Layanan\Controllers;

use App\Controllers\BaseController;
use Ramsey\Uuid\Uuid;

class Jabatan extends BaseController
{
    protected $folder_directory = "Modules\\Layanan\\Views\\jabatan\\";
    private $indukmodule = 'Tata Kelola Layanan';
    private $subindukmodule = '';
    private $title = 'Jabatan';
    private $module = 'layanan/jabatan';

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
}