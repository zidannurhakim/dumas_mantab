<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<?php function tanggal_indo($tanggal, $cetak_hari = false)
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
    if (empty($tanggal)) {
        return 'Tanggal tidak tersedia';
    }
    $split = explode('-', $tanggal);
    $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

    if ($cetak_hari) {
        $num = date('N', strtotime($tanggal));
        return $hari[$num] . ', ' . $tgl_indo;
    }
    return $tgl_indo;
} ?>
<?php
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
        $output .= ' pukul ' . substr($waktu, 0, 5) . ' WIB';
    }

    return $output;
}
?>
<style>
    /* CSS Kustom untuk Kotak Obrolan (dari kode sebelumnya) */
    body {
        background-color: #f0f2f5; /* Warna latar belakang umum */
        font-family: 'Inter', sans-serif; /* Menggunakan font Inter */
    }
    .chat-container {
        max-width: 100%; /* Sesuaikan dengan kolom Bootstrap */
        height: 70vh; /* Tinggi kotak obrolan */
        margin: 0; /* Hapus margin auto karena sudah diatur oleh kolom */
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden; /* Memastikan konten tidak meluber */
    }
    .chat-header {
        background-color: #ffffff; /* Warna primary Bootstrap */
        color: white;
        padding: 15px 20px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        font-size: 1.25rem;
        font-weight: bold;
        text-align: center;
    }
    .chat-messages {
        flex-grow: 1; /* Agar pesan mengambil sisa ruang */
        padding: 20px;
        overflow-y: auto; /* Mengaktifkan scroll jika pesan banyak */
        background-color: #e9ebee; /* Latar belakang area pesan */
    }
    .message-bubble {
        padding: 10px 15px;
        border-radius: 18px;
        margin-bottom: 10px;
        max-width: 80%;
        word-wrap: break-word; /* Memastikan teks tidak keluar batas */
    }
    .message-sent {
        background-color: #004030; /* Warna untuk pesan terkirim */
        margin-left: auto; /* Agar pesan ke kanan */
        text-align: right;
    }
    .message-received {
        background-color: #1B3C53; /* Warna untuk pesan diterima */
        margin-right: auto; /* Agar pesan ke kiri */
        text-align: left;
        border: 1px solid #dee2e6; /* Border tipis untuk pesan diterima */
    }
    .message-meta {
        font-size: 0.75rem;
        color: #ffffff; /* Warna teks abu-abu Bootstrap */
        margin-top: 3px;
    }
    .chat-input {
        padding: 15px 20px;
        border-top: 1px solid #dee2e6; /* Border atas input */
        display: flex;
        align-items: center;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .chat-input .form-control {
        border-radius: 20px; /* Input melengkung */
        flex-grow: 1;
        margin-right: 10px;
    }
    .chat-input .btn {
        border-radius: 20px; /* Tombol melengkung */
        padding: 8px 20px;
    }

    /* CSS Kustom untuk embed-responsive (dari kode sebelumnya) */
    .embed-responsive {
        position: relative;
        display: block;
        width: 100%;
        padding: 0;
        overflow: hidden;
        padding-top: 80%; /* Menambahkan lebih banyak ruang vertikal */
    }
    .embed-responsive .embed-responsive-item {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Responsifitas untuk layar kecil */
    @media (max-width: 768px) {
        .chat-container {
            margin: 15px 0; /* Sesuaikan margin di mobile */
            height: 85vh; /* Lebih tinggi di perangkat seluler */
        }
        .chat-header {
            font-size: 1.1rem;
            padding: 12px 15px;
        }
        .chat-messages {
            padding: 15px;
        }
        .chat-input {
            padding: 12px 15px;
        }
    }
</style>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
                <a href='<?= base_url('module/it'); ?>' class="btn btn-success">Kembali</a>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="<?= base_url('beranda') ?>"><?= env('APPNAME') ?></a></li>
                        <?php 
                        if($indukmodule == !null)
                        {
                            echo '<li class="breadcrumb-item">'.$indukmodule.'</li>';
                        } 
                        if($subindukmodule == !null)
                        {
                            echo '<li class="breadcrumb-item">'.$subindukmodule.'</li>';
                        }
                        ?>
                        <li class="breadcrumb-item"><?= $title; ?></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $subtitle; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <?php foreach($data AS $val => $data){ ?>
                    <?php if($data->data_status_kirim == "BELUM"){ ?>
                        <div class="col-md-12 mb-4 d-grid">
                            <button class="btn btn-sm btn-danger">Data Belum Dikirim!</button>
                        </div>
                    <?php }elseif($data->data_status_kirim == "KIRIM"){ ?>
                        <div class="col-md-12 mb-4 d-grid">
                            <a class="btn btn-sm btn-primary"><i data-lucide="check"></i> &nbsp;Data ini telah dikirim 
                                <br> Pada hari <?= tanggal_indo_datetime($data->data_status_kirim_update, true); ?>
                            </a> <br>
                        </div>
                    <?php }else{ ?>
                        <a class="btn btn-danger"><i data-lucide="send"></i> Flag Tidak Diketahui</a>
                    <?php } ?>
                    <p>Data Masuk Dari :</p>
                    <div class="col-md-12 mb-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="<?= base_url('assets/other/flag/'.$data->data_flag.'.png'); ?>" width="60px" class="img-fluid" alt="Flag">
                            </div>
                            <div class="col">
                                <h5><b><?= $data->data_nama; ?> - <?= $data->data_nip; ?> (<?= $data->data_peran; ?>) </b></h5>
                                <span><?= $data->data_email; ?> -  <?= $data->data_nomorhp; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <p>Metode<br><b><?= $data->data_metode; ?></b></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <p>Update Data<br><b><?= tanggal_indo_datetime($data->data_update, true); ?></b></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <p>Subjek<br><b><?= $data->data_subjek; ?></b></p>
                    </div>
                    <div class="col-md-12 mb-2">
                        <p>Pesan<br><b><?= $data->data_pesan; ?></b></p>
                    </div>
                    <div class="col-md-12">
                    <?php if(empty($data->data_lampiran)){ ?>
                        <p><code class="text-danger">*</code> File Lampiran Kosong</p>
                    <?php }else{ ?>
                    <div class="accordion accordion-flush border border-secondary" id="accNaskah">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="detHeadLampiran">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#accHeadLampiran" aria-expanded="false" aria-controls="accHeadLampiran">
                                    <i data-lucide="file"></i>&nbsp; File Lampiran
                                </button>
                            </h2>
                            <div id="accHeadLampiran" class="accordion-collapse collapse" aria-labelledby="detHeadLampiran"
                                data-bs-parent="#accNaskah">
                                <div class="accordion-body">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe src="<?= base_url(env('URL_LAMPIRAN').'/'.$data->data_lampiran); ?>" class="embed-responsive-item" style="border:none"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!-- isi disini -->
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3"> <!-- Tambahkan mb-3 untuk jarak bawah di mobile -->
        <div class="chat-container">
            <!-- <div class="chat-header">
                Nama Kontak / Nama Grup
            </div> -->
            <div class="chat-messages text-white">
                <!-- Contoh Pesan Diterima -->
                <div class="message-bubble message-received">
                    Halo! Apa kabar?
                    <div class="message-meta">10:00 AM</div>
                </div>

                <!-- Contoh Pesan Terkirim -->
                <div class="message-bubble message-sent">
                    Baik-baik saja, terima kasih! Anda bagaimana?
                    <div class="message-meta">10:02 AM</div>
                </div>

                <div class="message-bubble message-received">
                    Alhamdulillah sehat. Sedang apa?
                    <div class="message-meta">10:05 AM</div>
                </div>

                <div class="message-bubble message-sent">
                    Baru saja selesai mengerjakan proyek. Cukup melelahkan.
                    <div class="message-meta">10:07 AM</div>
                </div>

                <div class="message-bubble message-received">
                    Oh begitu. Semoga lancar ya proyeknya.
                    <div class="message-meta">10:10 AM</div>
                </div>
                    <div class="message-bubble message-sent">
                    Terima kasih! Ada sesuatu yang bisa saya bantu?
                    <div class="message-meta">10:12 AM</div>
                </div>
                    <div class="message-bubble message-received">
                    Tidak ada, hanya menyapa saja :)
                    <div class="message-meta">10:15 AM</div>
                </div>
                    <div class="message-bubble message-sent">
                    Baiklah, senang mengobrol dengan Anda!
                    <div class="message-meta">10:17 AM</div>
                </div>
            </div>
            <div class="chat-input">
                <input type="text" class="form-control" placeholder="Ketik pesan Anda...">
                <input type="file" class="form-control">
                <button class="btn btn-primary">Kirim</button>
            </div>
        </div>
    </div>
</div>