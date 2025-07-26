<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= lang('Errors.pageNotFound') ?> | <?= env('APPNAME') ?></title>
    <meta name="author" content="@zidannurhakim_" />
    <meta name="email" content="ahmadzidan.nh@gmail.com" />
    <meta name="Version" content="v1.0.0" />
    <link rel="icon" href="<?= base_url('assets/other/logo_man3bwi.png')?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url('assets/other/logo_man3bwi.png')?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= base_url('assets/other/logo_man3bwi.png')?>">
    <link href="<?= base_url('assets/admin/assets/css/style.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin/assets/css/icons.min.css'); ?>" rel="stylesheet" type="text/css">
    <script src="<?= base_url('assets/admin/assets/js/config.js'); ?>"></script>
</head>
<body>
    <div class="wrap">
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center min-vh-100">
                            <div class="w-100 d-block card shadow-lg rounded my-5 overflow-hidden">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-5">
                                            <div class="text-center mb-5">
                                                <a class="text-dark font-size-22 font-family-secondary" href="<?=base_url('')?>">
                                                    <b><h1><?= env('APPNAME') ?></h1></b>
                                                </a>
                                            </div>
    
                                            <div class="text-center">
                                                <img src="<?= base_url('assets/admin/assets/images/404-error.svg') ?>" alt="error" height="140">
                                                <h1 class="h4 mb-3 mt-4">
                                                    <?php if (ENVIRONMENT !== 'production') : ?>
                                                        <?= nl2br(esc($message)) ?>
                                                    <?php else : ?>
                                                        <?= lang('Errors.sorryCannotFind') ?>
                                                    <?php endif; ?>
                                                </h1>
                                                <p class="text-muted mb-4 w-75 m-auto">Silahkan Cek Kembali Atau Bisa Hubungi Admin.</p>
                                            </div>
    
                                            <div class="row mt-4">
                                                <div class="col-12 text-center">
                                                    <a class="btn btn-primary waves-effect waves-light" href="<?=base_url('')?>"><i
                                                            class="mdi mdi-home me-2"></i>Kembali</a>
                                                </div> <!-- end col -->
                                            </div>
                                            <!-- end row -->
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div> <!-- end .w-100 -->
                        </div> <!-- end .d-flex -->
                    </div> <!-- end col-->
                </div> <!-- end row -->
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/admin/assets/js/vendor.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/assets/js/app.js'); ?>"></script>
</body>
</html>
