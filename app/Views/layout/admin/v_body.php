<body>
    <div class="layout-wrapper">
        <div class="main-menu">
            <div class="logo-box">
                <a href="<?= base_url('beranda'); ?>" class="logo-light">
                    <img src="<?= base_url('assets/other/logo_man3bwi.png') ?>" alt="logo" class="logo-lg" height="38">
                    <img src="<?= base_url('assets/other/logo_man3bwi.png') ?>" alt="small logo" class="logo-sm" height="38">
                </a>
                <a href="<?= base_url('beranda'); ?>" class="logo-dark">
                    <img src="<?= base_url('assets/other/logo_man3bwi.png') ?>" alt="dark logo" class="logo-lg" height="38">
                    <img src="<?= base_url('assets/other/logo_man3bwi.png') ?>" alt="small logo" class="logo-sm" height="38">
                </a>
            </div>
            <div data-simplebar>
                <?php echo view('layout/admin/v_sidebar') ?>
                <div class="help-box">
                    <h5 class="text-muted font-size-15 mb-3">For Help &amp; Support</h5>
                    <p class="font-size-13"><span class="font-weight-bold">Web Aduan:</span> <br> <a href="https://dumas.man3banyuwangi.id/" target="_blank">dumas.man3banyuwangi.id</a></p>
                    <p class="mb-0 font-size-13"><span class="font-weight-bold">Email:</span> <br> mavic@man3banyuwangi.id</p>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="navbar-custom">
                <?php echo view('layout/admin/v_topbar') ?>
            </div>
            <div class="px-3">
                <?php echo view('layout/admin/v_view') ?>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div><script>document.write(new Date().getFullYear())</script> © <?= env('APPNAME') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end">
                                <p class="mb-0">Design & Develop by <a href="https://instagram.com/zidannurhakim_" target="_blank">A. Zidan Nur Hakim</a> | Powered with <span class="text-danger"><i data-lucide="heart"></i></span>&nbsp;MAVIC MAN 3 Banyuwangi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="<?= base_url('assets/admin/assets/js/vendor.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/assets/js/app.js'); ?>"></script>
    <script src="<?= base_url('assets/sweetalert2/dist/sweetalert2.all.min.js')?>"></script>
    <script src="<?= base_url('assets/sweetalert2/myscript.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/select2/js/select2.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-buttons/js/buttons.flash.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/datatables.net-select/js/dataTables.select.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/pdfmake/build/pdfmake.min.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/pdfmake/build/vfs_fonts.js')?>"></script>
    <script src="<?= base_url('assets/mazer/assets/extensions/choices.js/public/assets/scripts/choices.js')?>"></script>
    <script src="<?= base_url('assets/mazer/assets/static/js/pages/form-element-select.js')?>"></script>
    <script src="<?= base_url('assets/admin/assets/js/pages/datatables.js')?>"></script>
</body>

</html>