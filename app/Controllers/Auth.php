<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Google_Client;
use Modules\Konfigurasi\Models\SSOModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;


class Auth extends BaseController
{
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
        // is_logged();
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
            $data['title'] = '.:: Portal Login';
            $data['link'] = $this->googleClient->createAuthUrl();
            return view('auth/index', $data);
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

    // function loginSiam()
    // {
    //     $jwtToken = get_cookie('M-SSO-SESSION');
        
    //     if (empty($jwtToken)) {
    //         return redirect()->to('https://siam.uin-malang.ac.id');
    //     }

    //     $modelSSO = new SSOModel();

    //     $jwtKeyResult = $modelSSO->parameterSSO("jwt_key");
    //     $jwtAlgorithmResult = $modelSSO->parameterSSO("jwt_algorithm");

    //     if (empty($jwtKeyResult) || empty($jwtAlgorithmResult)) {
    //         return redirect()->to('https://siam.uin-malang.ac.id')->with('error', 'Kesalahan konfigurasi SSO.');
    //     }

    //     $jwtKey = $jwtKeyResult[0]->sso_value;
    //     $jwtAlgorithm = $jwtAlgorithmResult[0]->sso_value;

    //     try {
    //         $decodedToken = JWT::decode($jwtToken, new Key($jwtKey, $jwtAlgorithm));
    //     } catch (ExpiredException $e) {
    //         return redirect()->to('https://siam.uin-malang.ac.id')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
    //     } catch (SignatureInvalidException $e) {
    //         return redirect()->to('https://siam.uin-malang.ac.id')->with('error', 'Token tidak valid.');
    //     } catch (BeforeValidException $e) {
    //         return redirect()->to('https://siam.uin-malang.ac.id')->with('error', 'Token belum berlaku.');
    //     } catch (Exception $e) {
    //         return redirect()->to('https://siam.uin-malang.ac.id')->with('error', 'Terjadi kesalahan autentikasi.');
    //     }
        

    //     $modelLogin = new AuthModel();
    //     $user = $modelLogin->cekUser($decodedToken->usm_mail);

    //     if (!$user) {
    //         return redirect()->to('https://siam.uin-malang.ac.id')->with('error', 'Akun tidak terdaftar. Hubungi administrator.');
    //     }

    //     $user = $user[0];

    //     if ($user->usr_active !== 'Y') {
    //         return redirect()->to('/')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
    //     }

    //     session()->set([
    //         'usr_id'      => $user->usr_id,
    //         'usr_email'   => $user->usr_email,
    //         'usr_full'    => $user->usr_full,
    //         'usg_id'      => $user->usg_id,
    //         'usg_name'    => $user->usg_name,
    //         'usr_profile' => base_url('assets/other/logo.png'),
    //     ]);

    //     session()->setFlashdata('sweetAlert', [
    //         'message' => "Selamat Datang di Aplikasi SAFIRA",
    //         'icon'    => 'success'
    //     ]);

    //     return redirect()->to('/beranda');
    // }

    function bypassLogin()
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
            $data['title'] = '.:: Portal Login';
            $data['link'] = $this->googleClient->createAuthUrl();
            return view('auth/index', $data);
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

    function loginGoogle()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
        // dd($token);
        if (!isset($token['error'])) {
            $this->googleClient->setAccessToken($token['access_token']);
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            // dd($data);
            $modelLogin = new AuthModel();
            $cekUserLogin = $modelLogin->cekUser($data['email']);
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
