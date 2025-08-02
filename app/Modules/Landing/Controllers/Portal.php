<?php

namespace Modules\Landing\Controllers;

use App\Controllers\BaseController;
use Google_Client;
use Modules\Landing\Models\PortalModel;
use Ramsey\Uuid\Uuid;
use CodeIgniter\Email\Email;

class Portal extends BaseController
{
    protected $folder_directory = "Modules\\Landing\\Views\\";
    private $indukmodule = '';
    private $subindukmodule = '';
    private $title = '.:: Selamat Datang';
    private $module = '/';
    private $submoduls = array('/' => 'Portal Depan', 'cek-data' => 'Cek Data', 'statistik' => 'Statistik');

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
            $data['indukmodule'] = $this->indukmodule;
            $data['subindukmodule'] = $this->subindukmodule;
            $data['title'] = $this->title;
            $data['subtitle'] = 'Index';
            $data['module'] = $this->module;
            $data['submoduls'] = $this->submoduls;
            $data['subactive'] = '/';
            $data['view'] = $this->folder_directory .'index';
            $data['link'] = $this->googleClient->createAuthUrl();
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

    function prosestambah()
    {
        $uuid = Uuid::uuid4()->toString();
        $model = new PortalModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        $validation->setRules([
            'lay_id' => [
                'rules' => 'required', 
                'errors' => [
                    'required' => 'Jenis layanan wajib diisi.' 
                ]
            ],
            'data_metode' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Metode layanan wajib diisi.'
                ]
            ],
            'data_nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama wajib diisi.'
                ]
            ],
            'data_email' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Email wajib diisi.'
                ]
            ],
            'data_nomorhp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nomor HP WhatsApp wajib diisi.'
                ]
            ],
            'data_peran' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Peran wajib diisi.'
                ]
            ],
            'data_nip' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'NIS/NIP/NIPT wajib diisi.'
                ]
            ],
            'data_subjek' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Subjek wajib diisi.'
                ]
            ],
            'data_pesan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pesan wajib diisi.'
                ]
            ],
            'data_lampiran' => [ 
                'rules' => 'max_size[data_lampiran,10240]|ext_in[data_lampiran,pdf,jpg,png,jpeg]', 
                'errors' => [
                    'max_size' => 'Ukuran File Lampiran terlalu besar (maks 10MB).',
                    'ext_in' => 'Format File Lampiran tidak diizinkan. Hanya PDF, JPG, PNG, JPEG.'
                ]
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errorMessage = 'Validasi gagal: ';
            $errors = $validation->getErrors();
            foreach ($errors as $field => $error) {
                $errorMessage .= $error . '; '; 
            }

            return $this->response->setJSON([
                csrf_token() => $token, 
                'status' => 'error',
                'message' => $errorMessage, 
                'errors' => $errors, 
            ]);
        }

        $fileLampiran = $this->request->getFile('data_lampiran'); 
        $fileNameLampiran = null;
        $fileSizeLampiran = 0;
        $uploadPathLampiran = FCPATH.'uploads/lampiran/'; 

        if ($fileLampiran && $fileLampiran->isValid() && !$fileLampiran->hasMoved()) {
            $newNameLampiran = $fileLampiran->getRandomName();
            if (!is_dir($uploadPathLampiran)) {
                mkdir($uploadPathLampiran, 0777, true);
            }
            $fileLampiran->move($uploadPathLampiran, $newNameLampiran);
            $fileNameLampiran = $newNameLampiran;
            $fileSizeLampiran = $fileLampiran->getSize();
        } 
        $data = [
            'data_id' => $uuid,
            'lay_id' => $this->request->getPost('lay_id'),
            'data_metode' => $this->request->getPost('data_metode'),
            'data_nama' => $this->request->getPost('data_nama'),
            'data_email' => $this->request->getPost('data_email'),
            'data_nomorhp' => $this->request->getPost('data_nomorhp'),
            'data_peran' => $this->request->getPost('data_peran'),
            'data_nip' => $this->request->getPost('data_nip'),
            'data_subjek' => $this->request->getPost('data_subjek'),
            'data_pesan' => $this->request->getPost('data_pesan'),
            'data_lampiran' => $fileNameLampiran,
            'data_lampiran_size' => $fileNameLampiran ? (round($fileSizeLampiran / 1024, 2) . ' KB') : null,
            'data_status_kirim' => 'BELUM',
            'data_status_selesai' => 'BELUM',
            'data_flag' => 'SUCCESS',
            'data_update' => gmdate('Y-m-d H:i:s', time() + 25200),
        ];

        $email = \Config\Services::email(); 
        $email->initialize([
            'protocol'  => 'smtp',
            'SMTPHost'  => env('email.SMTPHost'),
            'SMTPUser'  => env('email.SMTPUser'),
            'SMTPPass'  => env('email.SMTPPass'),
            'SMTPPort'  => 587,
            'SMTPCrypto'=> 'tls',
            'mailType'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'CRLF'      => "\r\n",
        ]);
        $email_data = [
            'data_nama' => $data['data_nama'],
            'data_id' => $data['data_id'],
            'data_metode' => $data['data_metode'],
            'data_email' => $data['data_email'],
            'data_nip' => $data['data_nip'],
            'data_subjek' => $data['data_subjek'],
            'data_pesan' => $data['data_pesan'],
            'data_lampiran' => $data['data_lampiran'],
            'data_lampiran_size' => $data['data_lampiran_size']
        ];
        $emailContent = view('email/konfirmasi', $email_data);
        $email->setTo($data['data_email']);
        $email->setFrom(env('email.fromEmail'), env('email.fromName'));
        $email->setSubject('Terima Kasih Sudah Menggunakan DUMAS MAN 3 Banyuwangi');
        $email->setMessage($emailContent);
        try {
            if ($model->tambah($data)) {
                if ($email->send()) 
                {
                    return $this->response->setJSON([
                        csrf_token() => $token,
                        'status' => 'success',
                        'message' => 'Data berhasil ditambahkan.'
                    ]);
                } else {
                    return $this->response->setJSON([
                        csrf_token() => $token,
                        'status' => 'error',
                        'message' => 'Gagal Kirim Email.'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data ke database.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                csrf_token() => $token,
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ]);
        }
    }

    function data_jenis_layanan()
    {
        $search = $this->request->getPost('search');
        $model = new PortalModel();
        $result = $model->data_jenislayanan_select2($search);

        $data = [];
        $no = 0;
        foreach($result AS $val) 
        {
            $no++;
            $nama = $no.'. '.$val->lay_nama;
            $data[] = [
                'id' => $val->lay_id,
                'text' => $nama
            ];
        }
        return $this->response->setJSON([
            'results' => $data,
            csrf_token() => csrf_hash()
        ]);
    }
    
    function testemail()
    {
        $email = \Config\Services::email();
        $email->initialize([
            'protocol'  => 'smtp',
            'SMTPHost'  => env('email.SMTPHost'),
            'SMTPUser'  => env('email.SMTPUser'),
            'SMTPPass'  => env('email.SMTPPass'),
            'SMTPPort'  => 587,
            'SMTPCrypto'=> 'tls',
            'mailType'  => 'html', // Pastikan ini 'html' agar template HTML dirender dengan benar
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'CRLF'      => "\r\n",
        ]);

        $email_data = [
            'data_nama' => 'Ahmad Zidan Nur Hakim',
            'data_id' => '28926efe-008e-43c1-80dc-95bced79f98c',
            'data_metode' => 'PENGAJUAN',
            'data_email' => 'ahmadzidan.nh@gmail.com',
            'data_nip' => '8545688',
            'data_subjek' => 'Aplikasi SIMS Tidak Bekerja Dengan Baik',
            'data_pesan' => 'SIMS Error',
            'data_lampiran' => '', // Pastikan ini sesuai dengan template (kosong jika tidak ada)
            'data_lampiran_size' => '' // Pastikan ini sesuai dengan template (kosong jika tidak ada)
        ];

        // Muat template HTML email
        $emailContent = view('email/konfirmasi', $email_data); // Perhatikan: saya menggunakan 'emails/konfirmasi_dumas' sesuai struktur yang saya sarankan sebelumnya

        $email->setTo('ahmadzidan.nh@gmail.com');
        $email->setFrom(env('email.fromEmail'), env('email.fromName')); // Mengambil dari .env
        $email->setSubject('Tes Kirim Email');
        $email->setMailType('html'); // PENTING: Setel kembali ke 'html' agar email terkirim sebagai HTML
        $email->setMessage($emailContent);

        if ($email->send()) {
            // Jika berhasil, kembalikan respons JSON atau redirect
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Email berhasil dikirim.'
            ]);
            // Atau redirect:
            // return redirect()->to(base_url('some-success-page'));
        } else {
            // Jika gagal, kembalikan respons JSON dengan debugger
            $debugInfo = $email->printDebugger(['headers']);
            log_message('error', 'Gagal Kirim Email Test: ' . $debugInfo);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal kirim email test. Debug: ' . $debugInfo
            ]);
        }
    }
}