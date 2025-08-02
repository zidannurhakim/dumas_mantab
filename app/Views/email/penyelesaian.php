<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Data DUMAS MAN 3 Banyuwangi</title>
    <!-- Hapus link ke style.min.css karena tidak akan berfungsi di email klien -->
    <style>
        /* Global Reset & Base Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            color: #333333; /* Default text color */
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%; /* Ensure tables take full width */
        }
        td {
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%; /* Responsive images */
        }
        a {
            text-decoration: none;
            color: #0d6efd; /* Bootstrap primary blue for links */
        }

        /* Container */
        .container-email {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header-email {
            background-color: #212529; /* Bootstrap's $gray-900 (dark) */
            color: #ffffff;
            padding: 25px 20px;
            text-align: center;
        }
        .header-email h2 {
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 24px;
            color: #ffffff;
            font-weight: bold;
        }
        .header-email h3 {
            margin-top: 5px;
            margin-bottom: 0;
            font-size: 18px;
            color: #ffffff;
            font-weight: normal;
        }

        /* Content */
        .content-email {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .content-email p {
            margin-bottom: 15px;
        }

        /* Footer */
        .footer-email {
            background-color: #e9ecef; /* Bootstrap's $gray-200 */
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d; /* Bootstrap's $gray-600 */
        }

        /* Button */
        .button-email {
            display: inline-block;
            background-color: #0d6efd; /* Bootstrap primary blue */
            color: #ffffff !important; /* Force white text */
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid #0d6efd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .button-email:hover {
            background-color: #0b5ed7; /* Darker primary blue on hover */
            border-color: #0b5ed7;
        }

        /* Detail Item */
        .detail-item-email {
            margin-bottom: 10px;
            font-size: 14px;
            color: #212529;
        }
        .detail-item-email strong {
            display: inline-block;
            width: 120px; /* Fixed width for labels */
            font-weight: bold;
            vertical-align: top;
        }
        .detail-item-email span {
            display: inline-block;
            vertical-align: top;
        }

        /* Responsive Styles (Media Queries) */
        @media only screen and (max-width: 600px) {
            .container-email {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }
            .content-email, .header-email, .footer-email {
                padding: 20px !important;
            }
            .header-email h2 {
                font-size: 20px !important;
            }
            .header-email h3 {
                font-size: 16px !important;
            }
            .button-email {
                width: 100% !important;
                text-align: center !important;
                padding: 15px 0 !important;
            }
            .detail-item-email strong {
                width: 100% !important;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <table class="container-email" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td class="header-email">
                            <h2>Penyelesaian Data DUMAS</h2>
                            <h3>MAN 3 Banyuwangi</h3>
                        </td>
                    </tr>
                    <tr>
                        <td class="content-email text-black">
                            <p>Halo, <strong><?= esc($data_nama); ?></strong>.</p>
                            <p>Terima kasih telah menggunakan layanan DUMAS MAN 3 Banyuwangi. Detail Data Anda Sebagai Berikut : </p>

                            <div class="detail-item-email">
                                <strong>ID Data :</strong> <span><?= esc($data_id); ?></span>
                            </div>
                            <div class="detail-item-email">
                                <strong>Metode :</strong> <span><?= esc($data_metode); ?></span>
                            </div>
                            <div class="detail-item-email">
                                <strong>Email Anda :</strong> <span><?= esc($data_email); ?></span>
                            </div>
                            <div class="detail-item-email">
                                <strong>NIS/NIP/NIPT :</strong> <span><?= esc($data_nip); ?></span>
                            </div>
                            <div class="detail-item-email">
                                <strong>Subjek :</strong> <span><?= esc($data_subjek); ?></span>
                            </div>
                            <div class="detail-item-email">
                                <strong>Pesan Anda :</strong><br><span><?= nl2br(esc($data_pesan)); ?></span>
                            </div>

                            <?php if (!empty($data_lampiran)): ?>
                            <div class="detail-item-email">
                                <strong>Lampiran :</strong> <a href="<?= base_url(env('URL_LAMPIRAN').'/'.esc($data_lampiran)); ?>" target="_blank" style="color: #0d6efd;">Lihat Lampiran (<?= esc($data_lampiran_size); ?>)</a>
                            </div>
                            <?php endif; ?>

                            <p>Data telah diproses, serta dilakukan tindaklanjut dan penyelesaian.</p>
                            <p>Jika ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.</p>

                            <p>Hormat kami,<br><strong>IT Dumas MAN 3 Banyuwangi</strong></p>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="<?= base_url('/cek-data'); ?>" class="button-email" target="_blank">Cek Data Anda Disini</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer-email">
                            <p>&copy; <?= date('Y'); ?> DUMAS MAN 3 Banyuwangi. Semua Hak Dilindungi.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
