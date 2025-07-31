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
        max-width: 50%;
        word-wrap: break-word; /* Memastikan teks tidak keluar batas */
    }
    .message-sent {
        background-color: #DCF8C6; /* Warna untuk pesan terkirim */
        margin-left: auto; /* Agar pesan ke kanan */
        text-align: left;
    }
    .message-received {
        background-color: #ffffff; /* Warna untuk pesan diterima */
        margin-right: auto; /* Agar pesan ke kiri */
        text-align: left;
        border: 1px solid #dee2e6; /* Border tipis untuk pesan diterima */
    }
    .message-meta {
        font-size: 0.75rem;
        color: #191919; 
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
                <a href='<?= base_url('module/admin'); ?>' class="btn btn-success">Kembali</a>
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
    <?php foreach($data AS $val => $data){ ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
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
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <?php if($data->data_status_kirim == "BELUM"){ ?>
                    <div class="">
                        <h5>Pilih Penerima Data</h5>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="form-group">
                                        <label>User</label>
                                        <select name="usr_id" id="usr_id" class="form-select select2-user">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" id="submitForm" class="btn btn-primary">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php }elseif($data->data_status_kirim == "KIRIM"){ ?>
                    <div class="col-md-12 d-grid">
                        <a class="btn btn-sm btn-danger"><i data-lucide="badge-alert"></i> &nbsp;Data yang telah terkirim tidak bisa dikembalikan. 
                        </a>
                    </div>
                <?php }else{ ?>
                    <a class="btn btn-danger"><i data-lucide="send"></i> Flag Tidak Diketahui</a>
                <?php } ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <?php if($data->data_status_kirim == "BELUM"){ ?>
                    <div class="col-md-12 mb-4 d-grid">
                        <button class="btn btn-sm btn-danger">Obrolan Belum Tersedia</button>
                    </div>
                <?php }elseif($data->data_status_kirim == "KIRIM"){ ?>
                    <div class="col-md-12 d-grid">
                        <div class="accordion accordion-flush border border-secondary" id="accChat">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="detHeadChat">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#accHeadChat" aria-expanded="false" aria-controls="accHeadChat">
                                        <i data-lucide="file"></i>&nbsp; Buka Obrolan
                                    </button>
                                </h2>
                                <div id="accHeadChat" class="accordion-collapse collapse" aria-labelledby="detHeadChat" data-bs-parent="#accChat">
                                    <div class="accordion-body">
                                        <div class="chat-container">
                                            <div class="chat-messages">
                                                <div class="message-bubble message-received">
                                                    Halo! Apa kabar?
                                                    <div class="message-meta">10:00 AM</div>
                                                </div>
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
                                            <form id="kirimChat" enctype="multipart/form-data">
                                                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                                <div>
                                                    <div class="mb-2">
                                                        <textarea class="form-control" placeholder="Ketik pesan Anda..." rows="2"></textarea>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input type="file" class="form-control">
                                                    </div>
                                                    <div class="mb-2 d-grid">
                                                        <button class="btn btn-primary" id="submitChat">Kirim Pesan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }else{ ?>
                    <a class="btn btn-danger"><i data-lucide="send"></i> Flag Tidak Diketahui</a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        function updateCsrfToken(newToken) {
            $('input[name="<?= csrf_token() ?>"]').val(newToken);
        }
        $('.select2-user').select2({
            placeholder: "Pilih Nama Yang Akan Menerima Data",
            allowClear: true,
            ajax: {
                url: "<?= base_url('module/admin/data-user') ?>",
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        ['<?= csrf_token() ?>']: $('input[name="<?= csrf_token() ?>"]').val()
                    };
                },
                processResults: function(response) {
                    if (response['<?= csrf_token() ?>']) {
                        updateCsrfToken(response['<?= csrf_token() ?>']);
                    }
                    return {
                        results: response.results
                    };
                },
                cache: true
            }
        });
        $('#submitForm').click(function(e) {
            e.preventDefault(); 

            var form = $('#uploadForm')[0];
            var formData = new FormData(form); 
            $.ajax({
                url: '<?= base_url('module/admin/'.$data_id.'/proses-kirim') ?>',
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function(response) {
                    if (response['<?= csrf_token() ?>']) {
                        updateCsrfToken(response['<?= csrf_token() ?>']);
                    }

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            window.location.href = '<?= base_url('module/admin/'.$data_id.'/detail'); ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    let response;
                    try {
                        response = JSON.parse(xhr.responseText);
                        if (response['<?= csrf_token(); ?>']) 
                        { 
                            updateCsrfToken(response['<?= csrf_token(); ?>']);
                        }
                    } catch (e) {
                        console.error('Gagal parsing response error atau tidak ada CSRF token di response error:', e);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.'
                    });
                }
            });
        });
        $('#submitChat').click(function(e) {
            e.preventDefault(); 

            var form = $('#kirimChat')[0];
            var formData = new FormData(form); 
            $.ajax({
                url: '<?= base_url('module/admin/'.$data_id.'/proses-kirim') ?>',
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function(response) {
                    if (response['<?= csrf_token() ?>']) {
                        updateCsrfToken(response['<?= csrf_token() ?>']);
                    }

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            window.location.href = '<?= base_url('module/admin/'.$data_id.'/detail'); ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    let response;
                    try {
                        response = JSON.parse(xhr.responseText);
                        if (response['<?= csrf_token(); ?>']) 
                        { 
                            updateCsrfToken(response['<?= csrf_token(); ?>']);
                        }
                    } catch (e) {
                        console.error('Gagal parsing response error atau tidak ada CSRF token di response error:', e);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.'
                    });
                }
            });
        });
        
    });
</script>