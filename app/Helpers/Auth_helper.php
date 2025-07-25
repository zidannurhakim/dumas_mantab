<?php 
// app/Helpers/auth_helper.php

use CodeIgniter\HTTP\RedirectResponse;

if (!function_exists('is_allowed')) {
    function is_allowed($module): bool
    {
        $session = session();

        // Dapatkan array privmod dari session
        $privmod = $session->get('privmod');

        // Periksa apakah privmod ada dan merupakan array, lalu cek apakah module diizinkan
        if (is_array($privmod) && in_array($module, $privmod)) {
            return true; // Modul diizinkan
        } else {
            // Redirect dan hentikan eksekusi jika akses tidak diizinkan
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Anda tidak memiliki akses.',
                    'icon' => 'error'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            redirect()->to('/beranda')->send();
            exit; // Menghentikan eksekusi agar tidak melanjutkan proses setelah redirect
        }
    }
}
if (!function_exists('is_logged')) {
    function is_logged(): bool
    {
        $session = session();

        if ($session->has('usr_id')) {
            // Jika `usr_id` ada dalam session, lanjutkan
            return true;
        } else {
            // Jika `usr_id` tidak ada, redirect ke halaman login
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Silahkan login terlebih dahulu.',
                    'icon' => 'warning'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            redirect()->to('/')->send();
            exit;
        }
    }
}
