<?php

namespace Modules\Landing\Controllers;

use App\Controllers\BaseController;
use Google_Client;
use Modules\Landing\Models\CekDataModel;
use Ramsey\Uuid\Uuid;

class CekData extends BaseController
{
    protected $folder_directory = "Modules\\Landing\\Views\\cekdata\\";
    private $indukmodule = '';
    private $subindukmodule = '';
    private $title = '.:: Cek Data';
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
            $data['subactive'] = 'cek-data';
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

    function cek_data()
    {
        $model = new CekDataModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        // Validasi form
        $validation->setRules([
            'data_id' => 'required',
            'data_email' => 'required',
            'data_nip' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                csrf_token() => $token,
                'status' => 'error',
                'message' => 'Lengkapi Kembali Data dan Pastikan Tidak Ada Yang Kosong.',
                'errors' => $validation->getErrors(),
            ]);
        }


        $data_id = $this->request->getPost('data_id');
        $data_email = $this->request->getPost('data_email');
        $data_nip = $this->request->getPost('data_nip');

        $cekData = $model->cek_data($data_id, $data_email, $data_nip);

        try {
            if (!empty($cekData)) {
                $params = array(
                    'data_id' => $data_id,
                    'data_email' => $data_email,
                    'data_nip'=> $data_nip,
                );
                session()->set($params);
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'success',
                    'message' => 'Data berhasil diambil.',
                    'data_id' => $data_id
                ]);
            } else {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'error',
                    'message' => 'Data Tidak Ditemukan.'
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

    function detail_data($data_id)
    {
        if(empty(session()->data_id))
        {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Silahkan Cek Data Terlebih Dahulu',
                    'icon' => 'warning'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            return redirect()->to('/cek-data');
        }else
        {
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Berhasil Membuka Detail Data',
                    'icon' => 'success'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
            $model = new CekDataModel();
            $data['indukmodule'] = $this->indukmodule;
            $data['subindukmodule'] = $this->subindukmodule;
            $data['title'] = $this->title;
            $data['subtitle'] = 'Detail Data';
            $data['module'] = $this->module;
            $data['submoduls'] = $this->submoduls;
            $data['subactive'] = 'cek-data';
            $data['data'] = $model->data_id($data_id);
            $data['data_id'] = $data_id;
            $data['view'] = $this->folder_directory .'detail';
            return view('landing/templates', $data);
        }
    }

    function data_obrolan($data_id)
    {
        $model = new CekDataModel();
        $messages = $model->data_obrolan($data_id);
        return $this->response->setJSON($messages);
    }

    function proses_kirim_pesan($data_id)
    {
        $model = new CekDataModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();
        $uuid = Uuid::uuid4()->toString();

        $validation->setRules([
            'chat_pesan' => [
                'rules' => 'required', 
                'errors' => [
                    'required' => 'Pesan wajib diisi.' 
                ]
            ],
            'chat_lampiran' => [ 
                'rules' => 'max_size[chat_lampiran,10240]|ext_in[chat_lampiran,pdf,jpg,png,jpeg]', 
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

        $fileLampiran = $this->request->getFile('chat_lampiran'); 
        $fileNameLampiran = null;
        $fileSizeLampiran = 0;
        $uploadPathLampiran = FCPATH.'uploads/obrolan/'; 

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
            'chat_id' => $uuid,
            'data_id' => $data_id,
            'chat_pesan' => $this->request->getPost('chat_pesan'),
            'chat_lampiran' => $fileNameLampiran,
            'chat_lampiran_size' => $fileNameLampiran ? (round($fileSizeLampiran / 1024, 2) . ' KB') : null,
            'chat_jenis' => "USER",
            'chat_usr_id' => "USER",
            'chat_update' => gmdate('Y-m-d H:i:s', time() + 25200)
        ];

        try {
            if ($model->tambah_chat($data)) {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'success',
                    'message' => 'Pesan berhasil dikirim.'
                ]);
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

    function proses_kirim_rating($data_id)
    {
        $model = new CekDataModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();

        $data = [
            'data_rating' => $this->request->getPost('rating_value')
        ];

        try {
            if ($model->edit_data($data, $data_id)) {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'success',
                    'message' => 'Rating Berhasil Ditambahkan.',
                ]);
            } else {
                return $this->response->setJSON([
                    csrf_token() => $token,
                    'status' => 'error',
                    'message' => 'Data Tidak Ditemukan.'
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
}