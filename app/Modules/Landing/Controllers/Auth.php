<?php

namespace Modules\Landing\Controllers;

use App\Controllers\BaseController;
use Google_Client;
use Modules\Landing\Models\AuthModel;

class Auth extends BaseController
{
    protected $folder_directory = "Modules\\Landing\\Views\\";

    protected $googleClient;
    protected $users;

    function __construct()
    {
        $client_id_env = $_ENV['CLIENT_ID'];
        $client_secret_env = $_ENV['CLIENT_SECRET'];
        $redirect_url_env = $_ENV['REDIRECT_URL'];
        $this->googleClient=new Google_Client();
        $this->googleClient->setClientId($client_id_env);
        $this->googleClient->setClientSecret($client_secret_env);
        $this->googleClient->setRedirectUri($redirect_url_env);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        helper('auth');
        helper('cookie');
    }
    
    function loginGoogle()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
        // dd($token);
        if (!isset($token['error'])) {
            $this->googleClient->setAccessToken($token['access_token']);
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            $modelLogin = new AuthModel();
            $cekUserLogin = $modelLogin->cekUser($data['email']);
            if($cekUserLogin == null)
            {
                echo "<script>
                        alert('Mohon maaf, akun anda tidak terdaftar. Hubungi administrator.');
                        window.location='" . site_url('/') . "';
                    </script>";
            }else 
            {
                if($cekUserLogin[0]->usr_active == 'Y')
                {
                    $row = $cekUserLogin[0];
                    $params = array(
                        'usr_id' => $row->usr_id,
                        'usr_email' => $row->usr_email,
                        'usr_full'=> $row->usr_full,
                        'usg_id'=> $row->usg_id,
                        'usg_name'=> $row->usg_name,
                        'usr_profile' => base_url('assets/other/logo_man3bwi.png'),
                    );
                    session()->set($params);
                    // $this->set_privmod();
                    return redirect()->to('/beranda');
                } else
                {
                    echo "<script>
                        alert('Akun Anda tidak aktif. Silakan hubungi administrator.');
                        window.location='" . site_url('/') . "';
                    </script>";
                }
            }
        }
    }
    function refresh()
    {
        $modelLogin = new AuthModel();
        $email = session()->usr_email;
        $cekUserLogin = $modelLogin->cekUser($email);
        // print_r($cekUserLogin);
        if($cekUserLogin == null)
        {
            echo "<script>
                    alert('Mohon maaf, akun anda tidak terdaftar. Hubungi administrator.');
                    window.location='" . site_url('/') . "';
                </script>";
        }else 
        {
            if($cekUserLogin[0]->usr_active == 'Y')
            {
                session()->remove(['usr_id', 'usr_email', 'usg_name', 'usr_full', 'usg_id', 'usr_profile', 'sidebar', 'privmod']);
                $row = $cekUserLogin[0];
                // print_r($row);
                $params = array(
                    'usr_id' => $row->usr_id,
                    'usr_email' => $row->usr_email,
                    'usr_full'=> $row->usr_full,
                    'usg_id'=> $row->usg_id,
                    'usg_name'=> $row->usg_name,
                    'usr_profile' => base_url('assets/other/logo_man3bwi.png'),
                );
                session()->set($params);
                // $this->set_privmod();
                $sessFlashdata = [
                    'sweetAlert' => [
                        'message' => 'Refresh Session Berhasil',
                        'icon' => 'success'
                    ],
                ];
                session()->setFlashdata($sessFlashdata);
                return redirect()->to('/beranda');
            } else
            {
                echo "<script>
                    alert('Akun Anda tidak aktif. Silakan hubungi administrator.');
                    window.location='" . site_url('/') . "';
                </script>";
            }
        }
    }

    function logout()
    {
        session()->destroy();
        // delete_cookie('M-SSO-SESSION');
        // return redirect()->to('https://siam.uin-malang.ac.id');
        return redirect()->to('/');
    }

    function is_allowed($module)
	{
		if (in_array($module, $this->session->privmod))
			return true;
		else return redirect()->to('beranda');
	}
}
