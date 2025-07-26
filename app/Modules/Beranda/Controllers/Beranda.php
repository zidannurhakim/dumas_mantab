<?php

namespace Modules\Beranda\Controllers;

use App\Controllers\BaseController;
use Modules\Beranda\Models\BerandaModel;

class Beranda extends BaseController
{
    protected $folder_directory = "Modules\\Beranda\\Views\\";
    private $indukmodule = '';
    private $subindukmodule = '';
    private $title = 'Beranda';

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

}