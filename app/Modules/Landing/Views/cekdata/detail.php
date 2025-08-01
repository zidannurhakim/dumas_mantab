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
    } 
?>
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
    /* body {
        background-color: #f0f2f5; 
    } */
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
        max-width: 100%;
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
<style>
        .rating-buttons {
            display: flex;
            justify-content: center;
            gap: 10px; /* Jarak antar tombol */
            margin-top: 15px;
        }
        .rating-buttons .rating-btn {
            width: 50px; /* Lebar tombol */
            height: 50px; /* Tinggi tombol */
            border-radius: 50%; /* Membuat tombol bulat */
            font-size: 1.2rem;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s ease-in-out;
            border: 2px solid #0d6efd; /* Border default Bootstrap primary */
            color: #0d6efd;
            background-color: transparent;
        }
        .rating-buttons .rating-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            background-color: #e9ecef; /* Sedikit abu-abu saat hover */
        }
        .rating-buttons .rating-btn.selected {
            background-color: #0d6efd; /* Warna primary Bootstrap saat dipilih */
            color: white;
            border-color: #0d6efd;
            transform: none;
            box-shadow: none;
        }

        /* Warna khusus untuk setiap rating (opsional, untuk visual feedback) */
        .rating-buttons .rating-btn[data-value="1"].selected { background-color: #dc3545; border-color: #dc3545; } /* Merah */
        .rating-buttons .rating-btn[data-value="2"].selected { background-color: #fd7e14; border-color: #fd7e14; } /* Oranye */
        .rating-buttons .rating-btn[data-value="3"].selected { background-color: #ffc107; border-color: #ffc107; } /* Kuning */
        .rating-buttons .rating-btn[data-value="4"].selected { background-color: #198754; border-color: #198754; } /* Hijau */
        .rating-buttons .rating-btn[data-value="5"].selected { background-color: #0d6efd; border-color: #0d6efd; } /* Biru (Primary) */

    </style>
<?php foreach($data AS $val => $data){ ?>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if($data->data_status_kirim == "BELUM"){ ?>
                        <div class="col-md-12 mb-4 d-grid">
                            <button class="btn btn-sm btn-danger">Data Belum Dikirim!</button>
                        </div>
                    <?php }elseif($data->data_status_kirim == "KIRIM"){ ?>
                        <div class="col-md-12 mb-4 d-grid">
                            <a class="btn btn-sm btn-primary"><i data-lucide="check"></i> &nbsp;Data ini telah diteruskan 
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
    </div>
    <div>
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
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#accHeadChat" aria-expanded="true" aria-controls="accHeadChat">
                                        <i data-lucide="file"></i>&nbsp; Buka Obrolan
                                    </button>
                                </h2>
                                <div id="accHeadChat" class="accordion-collapse collapse show" aria-labelledby="detHeadChat" data-bs-parent="#accChat">
                                    <div class="accordion-body">
                                        <div class="chat-container">
                                            <div class="chat-messages text-black">
                                            </div>
                                            <?php if($data->data_status_selesai == "BELUM"){ ?>
                                                <form id="kirimChat" enctype="multipart/form-data">
                                                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                                    <div>
                                                        <div class="mb-2">
                                                            <textarea class="form-control" name="chat_pesan" placeholder="Ketik pesan Anda..." rows="2"></textarea>
                                                        </div>
                                                        <div class="mb-2">
                                                            <input type="file" class="form-control" name="chat_lampiran">
                                                        </div>
                                                        <div class="mb-2 d-grid">
                                                            <button class="btn btn-primary" id="submitChat">Kirim Pesan</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php }elseif($data->data_status_selesai == "SELESAI"){ ?>
                                                <div class="col-md-12 md-2 d-grid">
                                                    <button class="btn btn-sm btn-primary">Obrolan Telah Diselesaikan</button>
                                                </div>
                                            <?php }else{ ?>
                                                <div class="col-md-12 d-grid">
                                                    <button class="btn btn-sm btn-danger">Flag Tidak Diketahui</button>
                                                </div>
                                            <?php } ?>
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
    </div>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if(!empty($data->data_rating)){ ?>
                        <div class="col-md-12 md-2 d-grid">
                            <button class="btn btn-sm btn-primary">Rating Telah Diberikan. Terima Kasih</button>
                        </div>
                    <?php }elseif(empty($data->data_rating)){ ?>
                        <div class="col-md-12">
                            <form id="kirimRating" enctype="multipart/form-data">
                                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                <input type="hidden" name="rating_value" id="rating_value">
                                <div>
                                    <div class="mb-2">
                                        <div class="text-center">
                                            <h4>Bagaimana Pengalaman Anda?</h4>
                                            <p class="text-muted">Berikan penilaian Anda dari 1 (Buruk) hingga 5 (Sangat Baik).</p>
                                        </div>
                                        <label class="form-label visually-hidden">Berikan Penilaian Anda:</label>
                                        <div class="rating-buttons">
                                            <button type="button" class="btn rating-btn" data-value="1">1</button>
                                            <button type="button" class="btn rating-btn" data-value="2">2</button>
                                            <button type="button" class="btn rating-btn" data-value="3">3</button>
                                            <button type="button" class="btn rating-btn" data-value="4">4</button>
                                            <button type="button" class="btn rating-btn" data-value="5">5</button>
                                        </div>
                                    </div>
                                    <div class="mb-2 d-grid">
                                        <button class="btn btn-primary" id="submitRating">Kirim Penilaian</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php }else{ ?>
                        <div class="col-md-12 d-grid">
                            <button class="btn btn-sm btn-danger">Flag Tidak Diketahui</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script type="text/javascript">
    var currentDataId = '<?= $data_id ?? '' ?>';

    function formatChatTime(datetimeString) {
        const date = new Date(datetimeString);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    function fetchChatMessages() {
        if (!currentDataId) {
            console.warn("data_id tidak tersedia, tidak dapat mengambil obrolan.");
            return;
        }

        $.ajax({
            url: `<?= base_url('cek-data/') ?>${currentDataId}/data-obrolan`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var chatMessagesContainer = $('.chat-messages');
                chatMessagesContainer.empty();

                var sortedMessages = response.reverse();

                sortedMessages.forEach(function(message) {
                    var messageHtml = '';
                    var messageText = message.chat_pesan.replace(/\r\n|\r|\n/g, '<br>');
                    var messageTime = formatChatTime(message.chat_update);

                    var messageClass = (message.chat_jenis === 'USER') ? 'message-sent' : 'message-received';

                    messageHtml = `
                        <div class="message-bubble ${messageClass}">
                            ${messageText}
                            ${message.chat_lampiran ? `<br><a href="<?= base_url(env('URL_LAMPIRAN_OBROLAN').'/') ?>${message.chat_lampiran}" target="_blank" ><i data-lucide="paperclip" class="icon-sm"></i> Lampiran (${message.chat_lampiran_size})</a>` : ''}
                            <div class="message-meta">${messageTime} ${message.chat_jenis}</div>
                        </div>
                    `;
                    chatMessagesContainer.append(messageHtml);
                });

                chatMessagesContainer.scrollTop(chatMessagesContainer[0].scrollHeight);
                lucide.createIcons();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching chat messages:", status, error);
                $('.chat-messages').html('<div class="text-center text-danger">Gagal memuat pesan obrolan.</div>');
            }
        });
    }

    function updateCsrfToken(newToken) {
        $('input[name="<?= csrf_token() ?>"]').val(newToken);
    }

    $(document).ready(function() {
        fetchChatMessages();

        setInterval(fetchChatMessages, 10000);

        $('#submitChat').click(function(e) {
            e.preventDefault();

            var form = $('#kirimChat')[0];
            var formData = new FormData(form);
            formData.append('<?= csrf_token() ?>', $('input[name="<?= csrf_token() ?>"]').val());

            $.ajax({
                url: '<?= base_url('cek-data/') ?>' + currentDataId + '/proses-kirim-chat',
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
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            fetchChatMessages();
                            $('#chat_input_field').val('');
                            // $('#chat_lampiran_field').val(''); // Aktifkan jika ada input file lampiran
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
                        if (response['<?= csrf_token(); ?>']) {
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
        
        $('.rating-btn').on('click', function() {
            var selectedValue = $(this).data('value'); // Ambil nilai dari atribut data-value
            $('#rating_value').val(selectedValue); // Set nilai ke input hidden

            // Hapus kelas 'selected' dari semua tombol rating
            $('.rating-btn').removeClass('selected');

            // Tambahkan kelas 'selected' ke tombol yang baru saja diklik
            $(this).addClass('selected');

        });
        $('#submitRating').on('click', function(e) {
            e.preventDefault(); // Mencegah submit form default

            var form = $('#kirimRating')[0];
            var formData = new FormData(form);

            // Ambil nilai rating dari input hidden
            var finalRating = $('#kirimRating #rating_value').val();

            if (!finalRating) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Mohon pilih rating terlebih dahulu.'
                });
                return; // Hentikan proses jika rating belum dipilih
            }
            $.ajax({
                url: '<?= base_url('cek-data/') ?>' + currentDataId + '/proses-kirim-rating', // Pastikan currentDataId tersedia
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
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Opsional: Reset form rating atau nonaktifkan setelah berhasil
                            $('#kirimRating')[0].reset(); // Reset form
                            $('.rating-btn').removeClass('selected'); // Hapus seleksi
                            $('#kirimRating #rating_value').val(''); // Kosongkan nilai rating
                            window.location.href = '<?= base_url('cek-data'); ?>';
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
                        if (response['<?= csrf_token(); ?>']) {
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