<?php

use CodeIgniter\Router\RouteCollection;

/*
 * @var RouteCollection $routes
 */

// $routes->get('/', 'Auth::index');
// $routes->get('/login-siam', 'Auth::loginSiam');
// $routes->get('/login', 'Auth::bypassLogin');


$routes->group(
    '/', ['namespace' => '\Modules\Landing\Controllers'], function ($routes) {
        $routes->get('', 'Portal::index');
        $routes->post('data-jenis-layanan', 'Portal::data_jenis_layanan');
        $routes->post('layanan/proses-tambah', 'Portal::prosestambah');
        $routes->get('proses-login', 'Auth::loginGoogle');
        $routes->get('refresh-session', 'Auth::refresh');
        $routes->get('logout', 'Auth::logout');
    }
);

$routes->group(
    'beranda', ['namespace' => '\Modules\Beranda\Controllers'], function ($routes) {
        $routes->get('/', 'Beranda::index');
        $routes->get('/stat', 'Beranda::stat');
    }
);

$routes->group(
    'manajemen', ['namespace' => '\Modules\Manajemen\Controllers'], 
    function ($routes) 
    {
        $routes->get('level-user', 'Leveluser::index');
        $routes->post('level-user/data', 'Leveluser::data');
        $routes->get('level-user/tambah', 'Leveluser::tambah');
        $routes->post('level-user/proses-tambah', 'Leveluser::prosestambah');
        $routes->get('level-user/(:any)/edit', 'Leveluser::edit/$1');
        $routes->post('level-user/(:any)/proses-edit', 'Leveluser::update/$1');
        $routes->post('level-user/hapus', 'Leveluser::hapus');
        
        $routes->get('level-user/(:any)/module', 'Leveluser::module/$1');
        $routes->post('level-user/module_ajax', 'Leveluser::module_ajax');
        $routes->post('level-user/module/privmod_upd', 'Leveluser::privmod_upd');

        $routes->get('hak-akses', 'Hakakses::index');
        $routes->post('hak-akses/data', 'Hakakses::data');
        $routes->post('hak-akses/data-user', 'Hakakses::data_user');
        $routes->post('hak-akses/data-usergroup', 'Hakakses::data_level');
        $routes->get('hak-akses/tambah', 'Hakakses::tambah');
        $routes->post('hak-akses/proses-tambah', 'Hakakses::prosestambah');
        $routes->post('hak-akses/hapus', 'Hakakses::hapus');

        $routes->get('user', 'User::index');
        $routes->post('user/data', 'User::data');
        $routes->get('user/tambah', 'User::tambah');
        $routes->post('user/proses-tambah', 'User::prosestambah');
        $routes->get('user/(:any)/edit', 'User::edit/$1');
        $routes->post('user/(:any)/proses-edit', 'User::update/$1');
        $routes->post('user/hapus', 'User::hapus');
    }
);


$routes->group(
    'layanan', ['namespace' => '\Modules\Layanan\Controllers'], 
    function ($routes) 
    {
        $routes->get('jenis', 'Jenis::index');
        $routes->get('jenis/tambah', 'Jenis::tambah');
        $routes->post('jenis/tambah/proses-tambah', 'Jenis::prosestambah');
        $routes->post('jenis/data', 'Jenis::data');
        $routes->get('jenis/(:any)/edit', 'Jenis::edit/$1');
        $routes->post('jenis/(:any)/edit/proses-edit', 'Jenis::update/$1');
        $routes->post('jenis/hapus', 'Jenis::hapus');
        
        
        $routes->get('unit', 'Unit::index');
        $routes->get('unit/tambah', 'Unit::tambah');
        $routes->post('unit/data', 'Unit::data');
        $routes->post('unit/data-unit', 'Unit::data_unit');
        $routes->get('unit/data-chart', 'Unit::data_chart');
        $routes->post('unit/tambah/proses-tambah', 'Unit::prosestambah');
        $routes->post('unit/hapus', 'Unit::hapus');
        
        $routes->get('jabatan', 'Jabatan::index');
    }

);

$routes->group(
    'module', ['namespace' => '\Modules\Modules\Controllers'], 
    function ($routes) 
    {
        $routes->get('it', 'LayIT::index');
    }
);