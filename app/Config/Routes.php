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
    }

);
$routes->group(
    'sso', ['namespace' => '\Modules\SSO\Controllers'], 
    function ($routes) 
    {
        $routes->get('master-pegawai', 'MasterPegawai::index');
        $routes->post('master-pegawai/data', 'MasterPegawai::data');
        $routes->get('master-pegawai/sinkronisasi', 'MasterPegawai::sinkronisasi');

        $routes->get('master-mahasiswa', 'MasterMahasiswa::index');
        $routes->post('master-mahasiswa/data', 'MasterMahasiswa::data');
        $routes->get('master-mahasiswa/sinkronisasi', 'MasterMahasiswa::sinkronisasi');
    }
);


$routes->group(
    'konfigurasi', ['namespace' => '\Modules\Konfigurasi\Controllers'], 
    function ($routes) 
    {

        $routes->get('sso', 'SSO::index');
        $routes->get('sso/tambah', 'SSO::tambah');
        $routes->post('sso/data', 'SSO::data');
        $routes->post('sso/proses-tambah', 'SSO::prosestambah');
        $routes->get('sso/(:any)/edit', 'SSO::edit/$1');
        $routes->post('sso/(:any)/proses-edit', 'SSO::update/$1');
        $routes->get('sso/(:any)/hapus', 'SSO::hapus/$1');

        $routes->get('jenis-naskah', 'Jenisnaskah::index');
        $routes->get('jenis-naskah/tambah', 'Jenisnaskah::tambah');
        $routes->post('jenis-naskah/data', 'Jenisnaskah::data');
        $routes->post('jenis-naskah/proses-tambah', 'Jenisnaskah::prosestambah');
        $routes->get('jenis-naskah/(:any)/edit', 'Jenisnaskah::edit/$1');
        $routes->post('jenis-naskah/(:any)/proses-edit', 'Jenisnaskah::update/$1');
        $routes->get('jenis-naskah/(:any)/hapus', 'Jenisnaskah::hapus/$1');

        $routes->get('sifat-naskah', 'Sifatnaskah::index');
        $routes->get('sifat-naskah/tambah', 'Sifatnaskah::tambah');
        $routes->post('sifat-naskah/data', 'Sifatnaskah::data');
        $routes->post('sifat-naskah/proses-tambah', 'Sifatnaskah::prosestambah');
        $routes->get('sifat-naskah/(:any)/edit', 'Sifatnaskah::edit/$1');
        $routes->post('sifat-naskah/(:any)/proses-edit', 'Sifatnaskah::update/$1');
        $routes->get('sifat-naskah/(:any)/hapus', 'Sifatnaskah::hapus/$1');

        $routes->get('penandatanganan', 'Penandatanganan::index');
        $routes->get('penandatanganan/tambah', 'Penandatanganan::tambah');
        $routes->post('penandatanganan/data', 'Penandatanganan::data');
        $routes->post('penandatanganan/data-jabatan', 'Penandatanganan::data_jabatan');
        $routes->post('penandatanganan/proses-tambah', 'Penandatanganan::prosestambah');
        $routes->get('penandatanganan/(:any)/edit', 'Penandatanganan::edit/$1');
        $routes->post('penandatanganan/(:any)/proses-edit', 'Penandatanganan::update/$1');
        $routes->post('penandatanganan/hapus', 'Penandatanganan::hapus');

        $routes->get('tujuan-naskah', 'Tujuannaskah::index');
        $routes->get('tujuan-naskah/tambah', 'Tujuannaskah::tambah');
        $routes->post('tujuan-naskah/data', 'Tujuannaskah::data');
        $routes->post('tujuan-naskah/data-jabatan', 'Tujuannaskah::data_jabatan');
        $routes->post('tujuan-naskah/proses-tambah', 'Tujuannaskah::prosestambah');
        $routes->post('tujuan-naskah/hapus', 'Tujuannaskah::hapus');

        $routes->get('verifikator', 'Verifikator::index');
        $routes->get('verifikator/tambah', 'Verifikator::tambah');
        $routes->post('verifikator/data', 'Verifikator::data');
        $routes->post('verifikator/data-jabatan', 'Verifikator::data_jabatan');
        $routes->post('verifikator/proses-tambah', 'Verifikator::prosestambah');
        $routes->post('verifikator/hapus', 'Verifikator::hapus');

        $routes->get('tembusan', 'Tembusan::index');
        $routes->get('tembusan/tambah', 'Tembusan::tambah');
        $routes->post('tembusan/data', 'Tembusan::data');
        $routes->post('tembusan/data-jabatan', 'Tembusan::data_jabatan');
        $routes->post('tembusan/proses-tambah', 'Tembusan::prosestambah');
        $routes->post('tembusan/hapus', 'Tembusan::hapus');

        $routes->get('instruksi', 'Instruksi::index');
        $routes->get('instruksi/tambah', 'Instruksi::tambah');
        $routes->post('instruksi/data', 'Instruksi::data');
        $routes->post('instruksi/proses-tambah', 'Instruksi::prosestambah');
        $routes->get('instruksi/(:any)/edit', 'Instruksi::edit/$1');
        $routes->post('instruksi/(:any)/proses-edit', 'Instruksi::update/$1');
        $routes->get('instruksi/(:any)/hapus', 'Instruksi::hapus/$1');

        $routes->get('penomoran', 'Penomoran::index');
        $routes->get('penomoran/tambah', 'Penomoran::tambah');
        $routes->post('penomoran/data', 'Penomoran::data');
        $routes->post('penomoran/proses-tambah', 'Penomoran::prosestambah');
        $routes->get('penomoran/(:any)/edit', 'Penomoran::edit/$1');
        $routes->post('penomoran/(:any)/proses-edit', 'Penomoran::update/$1');
        $routes->get('penomoran/(:any)/hapus', 'Penomoran::hapus/$1');

        $routes->get('template', 'Template::index');
        $routes->get('template/tambah', 'Template::tambah');
        $routes->post('template/data', 'Template::data');
        $routes->post('template/data-jenis-naskah', 'Template::data_jenis_naskah');
        $routes->post('template/proses-tambah', 'Template::prosestambah');
        $routes->get('template/(:any)/edit', 'Template::edit/$1');
        $routes->post('template/(:any)/proses-edit', 'Template::update/$1');
        $routes->post('template/hapus', 'Template::hapus');
        
        $routes->get('area', 'Area::index');
        $routes->post('area/data', 'Area::data');
        $routes->get('area/tambah', 'Area::tambah');
        $routes->post('area/data-area-utama', 'Area::data_area_utama');
        $routes->post('area/data-area-pic', 'Area::data_area_pic');
        $routes->post('area/proses-tambah', 'Area::prosestambah');
        $routes->get('area/(:any)/edit', 'Area::edit/$1');
        $routes->post('area/(:any)/proses-edit', 'Area::update/$1');
        $routes->post('area/hapus', 'Area::hapus');
    }
);
$routes->group(
    'arsip', ['namespace' => '\Modules\Arsip\Controllers'], 
    function ($routes) 
    {
        $routes->get('induk', 'Induk::index');
        $routes->post('induk/data', 'Induk::data');
        $routes->post('induk/data-induk', 'Induk::data_induk');
        $routes->get('induk/tambah', 'Induk::tambah');
        $routes->post('induk/proses-tambah', 'Induk::prosestambah');
        $routes->get('induk/data-chart', 'Induk::chart');
        $routes->get('induk/(:any)/edit', 'Induk::edit/$1');
        $routes->post('induk/(:any)/proses-edit', 'Induk::update/$1');
        $routes->post('induk/hapus', 'Induk::hapus');

        $routes->get('klasifikasi', 'KlasifikasiArsip::index');
        $routes->post('klasifikasi/data', 'KlasifikasiArsip::data');
        $routes->get('klasifikasi/tambah', 'KlasifikasiArsip::tambah');
    }
);
$routes->group(
    'naskah-masuk', ['namespace' => '\Modules\Naskahmasuk\Controllers'], 
    function ($routes) 
    {
        $routes->get('registrasi', 'Registrasi::index');
        $routes->get('registrasi/tambah', 'Registrasi::tambah');
        $routes->post('registrasi/proses-tambah', 'Registrasi::prosestambah');

        $routes->get('daftar-naskah', 'Daftarnaskah::index');
        $routes->post('daftar-naskah/data', 'Daftarnaskah::data');
        $routes->get('daftar-naskah/(:any)/(:any)/detail', 'Daftarnaskah::detail/$1/$2');
        $routes->get('daftar-naskah/(:any)/(:any)/detail/form-disposisi', 'Daftarnaskah::disposisi/$1/$2');
        $routes->post('daftar-naskah/(:any)/(:any)/detail/form-disposisi/proses-tambah', 'Daftarnaskah::prosesdisposisi/$1/$2');
        $routes->get('daftar-naskah/(:any)/(:any)/detail/form-tembusan', 'Daftarnaskah::tembusan/$1/$2');
        $routes->post('daftar-naskah/(:any)/(:any)/detail/form-tembusan/proses-tambah', 'Daftarnaskah::prosestembusan/$1/$2');
        $routes->get('daftar-naskah/(:any)/(:any)/detail/form-selesai-mandiri', 'Daftarnaskah::selesai/$1/$2');
        $routes->post('daftar-naskah/(:any)/(:any)/detail/form-selesai-mandiri/proses-tambah', 'Daftarnaskah::prosesselesai/$1/$2');
        
        $routes->get('tembusan', 'Tembusan::index');
        $routes->post('tembusan/data', 'Tembusan::data');
        $routes->get('tembusan/(:any)/(:any)/(:any)/detail', 'Tembusan::detail/$1/$2/$3');
        $routes->get('tembusan/(:any)/(:any)/(:any)/detail/form-disposisi', 'Tembusan::disposisi/$1/$2/$3');
        $routes->post('tembusan/(:any)/(:any)/(:any)/detail/form-disposisi/proses-tambah', 'Tembusan::prosesdisposisi/$1/$2/$3');
        $routes->get('tembusan/(:any)/(:any)/(:any)/detail/form-penyelesaian', 'Tembusan::penyelesaian/$1/$2/$3');
        $routes->post('tembusan/(:any)/(:any)/(:any)/detail/form-penyelesaian/proses-tambah', 'Tembusan::prosespenyelesaian/$1/$2/$3');

        $routes->get('log-naskah', 'Lognaskah::index');
        $routes->post('log-naskah/data', 'Lognaskah::data');
        $routes->get('log-naskah/(:any)/detail', 'Lognaskah::detail/$1');
        $routes->post('log-naskah/(:any)/detail/proses-kirim', 'Lognaskah::kirim/$1');
        $routes->post('log-naskah/(:any)/detail/data-log', 'Lognaskah::data_log/$1');
        $routes->get('log-naskah/(:any)/edit', 'Lognaskah::edit/$1');
        $routes->post('log-naskah/(:any)/proses-edit', 'Lognaskah::update/$1');
        $routes->get('log-naskah/(:any)/hapus', 'Lognaskah::flagHapus/$1');
        
        $routes->get('disposisi', 'Disposisi::index');
        $routes->post('disposisi/data', 'Disposisi::data');
        $routes->get('disposisi/(:any)/(:any)/(:any)/detail', 'Disposisi::detail/$1/$2/$3');
        $routes->get('disposisi/(:any)/(:any)/(:any)/detail/form-disposisi', 'Disposisi::disposisi/$1/$2/$3');
        $routes->post('disposisi/(:any)/(:any)/(:any)/detail/form-disposisi/proses-tambah', 'Disposisi::prosesdisposisi/$1/$2/$3');
        $routes->get('disposisi/(:any)/(:any)/(:any)/detail/form-tembusan', 'Disposisi::tembusan/$1/$2/$3');
        $routes->post('disposisi/(:any)/(:any)/(:any)/detail/form-tembusan/proses-tambah', 'Disposisi::prosestembusan/$1/$2/$3');
        $routes->get('disposisi/(:any)/(:any)/(:any)/detail/form-penyelesaian', 'Disposisi::penyelesaian/$1/$2/$3');
        $routes->post('disposisi/(:any)/(:any)/(:any)/detail/form-penyelesaian/proses-tambah', 'Disposisi::prosespenyelesaian/$1/$2/$3');
        
        $routes->get('log-disposisi', 'Logdisposisi::index');
        $routes->post('log-disposisi/data', 'Logdisposisi::data');
        $routes->get('log-disposisi/(:any)/detail', 'Logdisposisi::detail/$1');
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