<?php

use CodeIgniter\Router\RouteCollection;

/*
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
// $routes->get('/login-siam', 'Auth::loginSiam');
$routes->get('/login', 'Auth::bypassLogin');
$routes->get('/proses-login', 'Auth::loginGoogle');
$routes->get('/refresh-session', 'Auth::refresh');
$routes->get('/logout', 'Auth::logout');


$routes->group(
    'abcd-passkey', ['namespace' => '\Modules\Abcd\Controllers'], function ($routes) {
        $routes->get('verifikasi', 'Verifikasi::index');
        $routes->post('verifikasi/cek-kredensial', 'Verifikasi::cekKredensial');
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
        $routes->get('level-user/(:any)/hapus', 'Leveluser::hapus/$1');
        
        $routes->get('level-user/(:any)/module', 'Leveluser::module/$1');
        $routes->post('level-user/module_ajax', 'Leveluser::module_ajax');
        $routes->post('level-user/module/privmod_upd', 'Leveluser::privmod_upd');

        $routes->get('hak-akses', 'Hakakses::index');
        $routes->post('hak-akses/data', 'Hakakses::data');
        $routes->get('hak-akses/tambah', 'Hakakses::tambah');
        $routes->post('hak-akses/proses-tambah', 'Hakakses::prosestambah');
        $routes->get('hak-akses/(:any)/edit', 'Hakakses::edit/$1');
        $routes->post('hak-akses/(:any)/proses-edit', 'Hakakses::update/$1');
        $routes->get('hak-akses/(:any)/hapus', 'Hakakses::hapus/$1');

        $routes->get('user', 'User::index');
        $routes->post('user/data', 'User::data');
        $routes->get('user/tambah', 'User::tambah');
        $routes->post('user/proses-tambah', 'User::prosestambah');
        $routes->get('user/(:any)/edit', 'User::edit/$1');
        $routes->post('user/(:any)/proses-edit', 'User::update/$1');
        $routes->get('user/(:any)/hapus', 'User::hapus/$1');

        $routes->get('sso-user', 'SSOUser::index');
        $routes->post('sso-user/data', 'SSOUser::data');
        $routes->post('sso-user/master-data', 'SSOUser::masterdata');
        $routes->get('sso-user/tambah', 'SSOUser::tambah');
        $routes->post('sso-user/proses-tambah', 'SSOUser::prosestambah');
        $routes->get('sso-user/(:any)/(:any)/hapus', 'SSOUser::hapus/$1/$2');
        
        
        $routes->get('pass-key', 'Passkey::index');
        $routes->get('pass-key/tambah', 'Passkey::tambah');
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
    }

);

$routes->group(
    'naskah-keluar', ['namespace' => '\Modules\Naskahkeluar\Controllers'], 
    function ($routes) 
    {
        $routes->get('registrasi', 'Registrasi::index');
        $routes->get('registrasi/tambah', 'Registrasi::tambah');
        $routes->post('registrasi/proses-tambah', 'Registrasi::prosestambah');
        $routes->post('registrasi/data-subsatker', 'Registrasi::data_subsatker');
        $routes->post('registrasi/data-jenis-naskah', 'Registrasi::data_jenis_naskah');
        $routes->post('registrasi/data-sifat-naskah', 'Registrasi::data_sifat_naskah');
        $routes->post('registrasi/data-tujuan-naskah-internal', 'Registrasi::data_tujuannaskah_internal');
        $routes->post('registrasi/data-tembusan-internal', 'Registrasi::data_tembusan_internal');
        $routes->post('registrasi/data-verifikator', 'Registrasi::data_verifikator');
        $routes->post('registrasi/data-penandatanganan', 'Registrasi::data_penandatanganan');
        
        
        $routes->get('log-naskah', 'Lognaskah::index');
        $routes->post('log-naskah/data', 'Lognaskah::data');
        $routes->post('log-naskah/data-log', 'Lognaskah::data_log');
        $routes->post('log-naskah/prepare-edit', 'Lognaskah::prepare_edit');
        $routes->get('log-naskah/edit', 'Lognaskah::edit');
        $routes->post('log-naskah/proses-edit', 'Lognaskah::prosesEdit');
        $routes->post('log-naskah/prepare-lihat', 'Lognaskah::prepare_lihat');
        $routes->get('log-naskah/lihat', 'Lognaskah::lihat');
        $routes->post('log-naskah/kirim', 'Lognaskah::kirim');

        $routes->get('verifikasi', 'Verifikasi::index');
    }
);