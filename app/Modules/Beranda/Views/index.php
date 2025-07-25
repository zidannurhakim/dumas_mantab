<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <img src="<?= base_url('assets/other/logo_man3bwi.png'); ?>" alt="logo-uin" width="16%">
                    </div>
                    <div><br></div>
                    <div>
                        <h2 class="text-center "><b>MAN 3 Banyuwangi</b></h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- diisi disini -->
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>