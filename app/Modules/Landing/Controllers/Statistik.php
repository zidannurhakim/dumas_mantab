<?php

namespace Modules\Landing\Controllers;

use App\Controllers\BaseController;
use Ramsey\Uuid\Uuid;

class Statistik extends BaseController
{
    protected $folder_directory = "Modules\\Landing\\Views\\statistik\\";
    private $indukmodule = '';
    private $subindukmodule = '';
    private $title = '.:: Statistik';
    private $module = '/';
    private $submoduls = array('/' => 'Portal Depan', 'cek-data' => 'Cek Data', 'statistik' => 'Statistik');

    protected $googleClient;
    protected $users;

    function __construct()
    {
        helper('auth');
        helper('cookie');
    }

    function index()
    {
        if(empty(session()->usr_id))
        {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Silahkan login terlebih dahulu',
                    'icon' => 'warning'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            session()->remove(['data_id', 'data_email', 'data_nip']);
            $data['indukmodule'] = $this->indukmodule;
            $data['subindukmodule'] = $this->subindukmodule;
            $data['title'] = $this->title;
            $data['subtitle'] = 'Index';
            $data['module'] = $this->module;
            $data['submoduls'] = $this->submoduls;
            $data['subactive'] = 'statistik';
            $data['view'] = $this->folder_directory .'index';
            return view('landing/templates', $data);
        }else
        {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Anda sudah login',
                    'icon' => 'success'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            return redirect()->to('/beranda');
        }
    }
}