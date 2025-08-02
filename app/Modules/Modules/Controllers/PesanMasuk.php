<?php

namespace Modules\Modules\Controllers;

use App\Controllers\BaseController;
use Modules\Modules\Models\PesanMasukModel;
use Ramsey\Uuid\Uuid;

class PesanMasuk extends BaseController
{
    protected $folder_directory = "Modules\\Modules\\Views\\pesanmasuk\\";
    private $indukmodule = 'Modules';
    private $subindukmodule = 'Layanan';
    private $title = 'Pesan Masuk';
    private $module = 'module/pesan-masuk';

    function __construct()
    {
        helper('auth');
        is_logged();
        is_allowed($this->module);
    }

    function index()
    {
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Index';
        $data['view'] = $this->folder_directory .'index';
        return view('layout/admin/templates', $data);
    }

    function data()
    {
        $request = \Config\Services::request();
        $model = new PesanMasukModel();

        $limit = $request->getPost('length'); 
        $start = $request->getPost('start'); 
        $orderColumnIndex = $request->getPost('order')[0]['column']; 
        $orderDirection = $request->getPost('order')[0]['dir'];
        $search = $request->getPost('search')['value'];

        $columns = [
            'b.data_id',
            'c.lay_nama',
            'b.data_metode',
            'b.data_nama',
            'b.data_nip',
            'b.data_peran',
            'b.data_email',
            'b.data_nomorhp',
            'b.data_subjek',
            'u.usr_full AS log_usr_full_pengirim',
            'u.usr_nip AS log_usr_nip_pengirim',
            'u.usr_email AS log_usr_email_pengirim',
            'u.usr_nomorhp AS log_usr_nomorhp_pengirim',
            'a.log_pesan',
            'b.data_status_kirim_update',
        ];
        $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'data_id';

        $totalData = $model->countAll();
        $totalFiltered = $totalData;

        if (empty($search)) {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection);
        } else {
            $result = $model->data($limit, $start, $orderColumn, $orderDirection, $search);
            $totalFiltered = $model->countFiltered($search);
        }

        $data = [];
        $count = $start;
        $statusBaca = null;
        foreach ($result as $val) {
            $pengirim = '<b>'.$val->data_nama.'</b> <br>NIS/NIP : '.$val->data_nip.' <br>Peran : '.$val->data_peran.' <br>Email : '.$val->data_email.' <br>Nomor HP : '.$val->data_nomorhp;
            $data_pengirim = '<b>'.$val->log_usr_full_pengirim.'</b> <br>NIS/NIP : '.$val->log_usr_nip_pengirim.' <br>Email : '.$val->log_usr_email_pengirim.' <br>Nomor HP : '.$val->log_usr_nomorhp_pengirim.' <br> Dikirim : '.$this->tanggal_indo_datetime($val->data_status_kirim_update, true);
            $count++;
            if($val->log_status_baca == "BACA")
            {
                $statusBaca = '<span class="badge bg-primary">'.$val->log_status_baca.'</span>';
            }elseif($val->log_status_baca == "BELUM")
            {
                $statusBaca = '<span class="badge bg-danger">'.$val->log_status_baca.'</span>';
            }
            $row = [
                'no' => $count,
                'lay_nama' => $val->lay_nama .'<br>'. $val->data_metode,
                'data_nama' => wordwrap($pengirim, 40, "<br>", false),
                'data_subjek' => wordwrap($val->data_subjek, 20, "<br>", false),
                'data_pengirim' => wordwrap($data_pengirim, 45, "<br>", false),
                'data_status_baca' => $statusBaca,
                'data_update' => $this->tanggal_indo_datetime($val->data_update, true),
                'aksi' => '
                    <a class="btn btn-success btn-sm waves-effect waves-light" href="' . base_url('module/pesan-masuk/'.$val->data_id.'/detail') .'">
                        <i data-lucide="eye"></i> Lihat Data
                    </a>'

            ];
            $data[] = $row;
        }

        $token = csrf_hash();

        $json_data = [
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
            "csrfHash" => $token
        ];

        return $this->response->setJSON($json_data);
    }

    function detail($id)
    {
        $model = new PesanMasukModel();
        $getData = $model->data_buka_pesan($id);
        if(!empty($getData))
        {
            $logId = $getData[0]->log_id;

            $data = [
                'log_status_baca' => 'BACA',
                'log_update_baca' => gmdate('Y-m-d H:i:s', time() + 25200)
            ];
            $model->edit_log($data, $logId);
            $sessFlashdata = [
                'sweetAlert' => [
                    'message' => 'Pembaharuan Status Baca Berhasil',
                    'icon' => 'success'
                ],
            ];
            session()->setFlashdata($sessFlashdata);
        }
        $data['data_id'] = $id;
        $data['master_data_id'] = $id;
        $data['data'] = $model->data_id($id);
        $data['indukmodule'] = $this->indukmodule;
        $data['subindukmodule'] = $this->subindukmodule;
        $data['title'] = $this->title;
        $data['subtitle'] = 'Detail Data';
        $data['view'] = $this->folder_directory .'detail';
        return view('layout/admin/templates', $data);
    }

    function proses_kirim_pesan($data_id)
    {
        $model = new PesanMasukModel();
        $getData = $model->data_tindaklanjut($data_id);
        if(!empty($getData))
        {
            $logId = $getData[0]->log_id;

            $data = [
                'log_flag_tindaklanjut' => 'SUDAH',
            ];
            $model->edit_log($data, $logId);
        }
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
            'chat_jenis' => "PENGELOLA",
            'chat_usr_id' => session()->usr_id,
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

    function data_obrolan($data_id)
    {
        $model = new PesanMasukModel();
        $messages = $model->data_obrolan($data_id);
        return $this->response->setJSON($messages);
    }

    function proses_selesai($data_id)
    {
        $model = new PesanMasukModel();
        $validation = \Config\Services::validation();
        $token = csrf_hash();
        $uuid = Uuid::uuid4()->toString();

        // Validasi form
        $validation->setRules([
            'usr_id' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                csrf_token() => $token,
                'status' => 'error',
                'message' => 'User ID Tidak Tersedia.',
                'errors' => $validation->getErrors(),
            ]);
        }

        $dataLog = [
            'log_id' => $uuid,
            'data_id' => $data_id,
            'log_usr_id_pengirim' => session()->usr_id,
            'log_usr_id_tujuan' => 'KOSONG',
            'log_tujuan_jenis' => "SELESAI",
            'log_hal' => "PENYELESAIAN",
            'log_pesan' => "Sudah Ditindaklanjuti",
            'log_lampiran' => null,
            'log_status_baca' => "BACA",
            'log_update_baca' => gmdate('Y-m-d H:i:s', time() + 25200),
            'log_flag_tindaklanjut' => "SUDAH",
            'log_update' => gmdate('Y-m-d H:i:s', time() + 25200)
        ];

        $update_data = [
            'data_status_selesai' => "SELESAI",
            'data_flag' => "PRIMARY",
        ];
        $kirimEmail = $model->data_id($data_id);

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
        $siapKirim = $kirimEmail[0];
        $email_data = [
            'data_id' => $siapKirim->data_id,
            'data_nama' => $siapKirim->data_nama,
            'data_metode' => $siapKirim->data_metode,
            'data_email' => $siapKirim->data_email,
            'data_nip' => $siapKirim->data_nip,
            'data_subjek' => $siapKirim->data_subjek,
            'data_pesan' => $siapKirim->data_pesan,
            'data_lampiran' => $siapKirim->data_lampiran,
            'data_lampiran_size' => $siapKirim->data_lampiran_size
        ];
        $emailContent = view('email/penyelesaian', $email_data);
        $email->setTo($siapKirim->data_email);
        $email->setFrom(env('email.fromEmail'), env('email.fromName'));
        $email->setSubject('Terima Kasih Sudah Menggunakan DUMAS MAN 3 Banyuwangi');
        $email->setMessage($emailContent);
        try {
            if ($model->tambah_log($dataLog)) {
                $model->edit_data($update_data, $data_id);
                if ($email->send()) 
                {
                    return $this->response->setJSON([
                        csrf_token() => $token,
                        'status' => 'success',
                        'message' => 'Data berhasil diselesaikan.'
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

    function tanggal_indo_datetime($datetime, $cetak_hari = false)
    {
        $hari = array(
            1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        if (empty($datetime)) {
            return 'Tanggal tidak tersedia';
        }

        // Pisahkan tanggal dan waktu
        $datetime_parts = explode(' ', $datetime);
        $tanggal = $datetime_parts[0];
        $waktu = isset($datetime_parts[1]) ? $datetime_parts[1] : '';

        $split = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

        $output = $tgl_indo;

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            $output = $hari[$num] . ', ' . $tgl_indo;
        }

        if (!empty($waktu)) {
            // Format waktu jadi `08:56 WIB`
            $output .= '<br>pukul ' . substr($waktu, 0, 5) . ' WIB';
        }

        return $output;
    }
}