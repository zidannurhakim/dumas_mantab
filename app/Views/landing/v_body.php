
<body>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center min-vh-100">
                        <div class="w-100 d-block card shadow-lg rounded my-5 overflow-hidden">
                            <div class="row">
                                <div class="col-lg-5 d-none d-lg-block bg-register rounded-left"></div>
                                <div class="col-lg-7">
                                    <div class="p-5">
                                        <div class="text-center w-75 mx-auto auth-logo mb-4">
                                            <a href="<?= base_url('beranda'); ?>" class="logo-dark">
                                                <span><img src="<?= base_url('assets/other/logo_man3bwi.png') ?>" alt="" height="80"></span>
                                            </a>
                                            
                                            <a href="<?= base_url('beranda'); ?>" class="logo-light">
                                                <span><img src="<?= base_url('assets/other/logo_man3bwi.png') ?>" alt="" height="80"></span>
                                            </a>
                                        </div>
                                        <?php echo view('landing/v_view') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/admin/assets/js/vendor.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/assets/js/app.js'); ?>"></script>
    <script src="<?= base_url('assets/sweetalert2/dist/sweetalert2.all.min.js')?>"></script>
    <script src="<?= base_url('assets/sweetalert2/myscript.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/select2/js/select2.min.js'); ?>"></script>
</body>
</html>