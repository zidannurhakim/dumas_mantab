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
        $routes->get('tes-email', 'Portal::testemail');
        $routes->get('proses-login', 'Auth::loginGoogle');
        $routes->get('refresh-session', 'Auth::refresh');
        $routes->get('logout', 'Auth::logout');
        $routes->get('cek-data', 'CekData::index');
        $routes->post('cek-data/proses-pengecekan', 'CekData::cek_data');
        $routes->get('cek-data/(:any)/detail-data', 'CekData::detail_data/$1');
        $routes->get('cek-data/(:any)/data-obrolan', 'CekData::data_obrolan/$1');
        $routes->post('cek-data/(:any)/proses-kirim-chat', 'CekData::proses_kirim_pesan/$1');
        $routes->post('cek-data/(:any)/proses-kirim-rating', 'CekData::proses_kirim_rating/$1');
        $routes->get('statistik', 'Statistik::index');
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
        
        $routes->get('akses-admin', 'AksesAdmin::index');
        $routes->post('akses-admin/data', 'AksesAdmin::data');
        $routes->get('akses-admin/(:any)/module', 'AksesAdmin::module/$1');
        $routes->post('akses-admin/module-ajax', 'AksesAdmin::module_ajax');
        $routes->post('akses-admin/module/privmod-upd', 'AksesAdmin::privmod_upd');
        
        $routes->get('jabatan', 'Jabatan::index');
    }
    
);

$routes->group(
    'module', ['namespace' => '\Modules\Modules\Controllers'], 
    function ($routes) 
    {
        $routes->get('admin', 'Admin::index');
        $routes->post('admin/data', 'Admin::data');
        $routes->post('admin/data-layanan', 'Admin::data_layanan');
        $routes->post('admin/data-user', 'Admin::data_user');
        $routes->post('admin/(:any)/proses-kirim', 'Admin::proses_kirim/$1');
        $routes->get('admin/(:any)/detail', 'Admin::detail/$1');
        
        $routes->get('pesan-masuk', 'PesanMasuk::index');
        $routes->post('pesan-masuk/data', 'PesanMasuk::data');
        $routes->get('pesan-masuk/(:any)/detail', 'PesanMasuk::detail/$1');
        $routes->post('pesan-masuk/(:any)/proses-kirim-chat', 'PesanMasuk::proses_kirim_pesan/$1');
        $routes->get('pesan-masuk/(:any)/data-obrolan', 'PesanMasuk::data_obrolan/$1');
        $routes->post('pesan-masuk/(:any)/proses-selesai', 'PesanMasuk::proses_selesai/$1');
    }
);