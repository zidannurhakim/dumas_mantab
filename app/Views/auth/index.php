<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="UIN Maulana Malik Ibrahim Malang" />
    <meta name="keywords" content="" />
    <meta name="author" content="@zidannurhakim_" />
    <meta name="email" content="ahmadzidan.nh@gmail.com" />
    <meta name="Version" content="v1.0.0" />
    <title><?= $title ?> | <?= env('APPNAME') ?></title>
    <link rel="icon" href="<?= base_url('assets/other/logo_man3bwi.png')?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url('assets/other/logo_man3bwi.png')?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= base_url('assets/other/logo_man3bwi.png')?>">
    <link href="<?= base_url('assets/admin/assets/libs/morris.js/morris.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/admin/assets/css/style.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin/assets/css/icons.min.css'); ?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?= base_url('assets/admin/datatables.net-bs5/css/dataTables.bootstrap5.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/compiled-dt/table-datatable-jquery.css')?>">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .login-container {
            max-width: 700px;
            margin: 0 auto;
        }
        .background {
            /* background: url('assets/other/siakad.png'); Background pattern */
            /* background-color: rgba(52, 150, 154, 0.5);
            background-size: auto; */
            padding-top: 50px;
            padding-bottom: 50px;
        }
        .login-box {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .info-section {
            background-color: rgba(17, 63, 103, 0.5);
            padding: 15px;
            border-radius: 8px;
        }
        .login-title {
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            color: #34699A;
        }
        .info-section h6 {
            color: #ffffff;
            font-weight: bold;
        }
        .btn-login {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="login-container">
            <!-- Login Section -->
            <div class="login-box">
                <div class="text-center mb-3">
                    <img src="<?= base_url('assets/other/logo_man3bwi.png'); ?>" width="10%">
                </div>
                <?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
                    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
                <?php endif; ?>
                <h1 class="login-title">Dumas MAN 3 Banyuwangi</h1>
                <p class="text-center"><small>Barangsiapa yang tidak mensyukuri yang sedikit, maka ia tidak akan mampu mensyukuri sesuatu yang banyak — HR. Ahmad</small></p>
                <div class="row">
                    <div class="col-md-12">
                        <a href="<?= $link; ?>" class="btn btn-primary btn-login"> Sign in with Google</a>
                    </div>
                </div>
                <div class="text-center mt-3">
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section mt-4">
                <h6>Pengumuman</h6>
                <ul class="text-white">
                    <li>Dumas Dapat Diakses Melalui SSO MAN 3 Banyuwangi.</li>
                    <li>Fitur Log Update 1.2.</li>
                </ul>
                <!-- <h6>Layanan Baru</h6> -->
                <!-- <ul>
                    <li>Pegawai sekarang dapat mengajukan cuti melalui SAFIRA</li>
                    <li>Layanan Surat Akademik <a href="mailto:akademik@uin-malang.ac.id">akademik@uin-malang.ac.id</a></li>
                    <li>Layanan Administrasi Keuangan Mahasiswa <a href="mailto:studentfinance.uin-malang.ac.id">studentfinance.uin-malang.ac.id</a></li>
                </ul> -->
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/admin/assets/js/vendor.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/assets/js/app.js'); ?>"></script>
    <script src="<?= base_url('assets/sweetalert2/dist/sweetalert2.all.min.js')?>"></script>
    <script src="<?= base_url('assets/sweetalert2/myscript.js')?>"></script>
</body>
</html>
