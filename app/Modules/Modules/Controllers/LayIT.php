<?php

namespace Modules\Modules\Controllers;

use App\Controllers\BaseController;
use Ramsey\Uuid\Uuid;

class LayIT extends BaseController
{
    protected $folder_directory = "Modules\\Modules\\Views\\it\\";
    private $indukmodule = 'Modules';
    private $subindukmodule = 'Layanan';
    private $title = 'IT';
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
}