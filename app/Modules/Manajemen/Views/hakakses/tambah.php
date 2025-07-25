<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
                <a href='<?= base_url('manajemen/hak-akses'); ?>' class="btn btn-success">Kembali</a>
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
            <h4>Form <?= $title; ?></h4>
            <form action="<?= base_url('manajemen/hak-akses/proses-tambah'); ?>" enctype="multipart/form-data" method="post">
            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User</label>
                            <select name="usr_id" class="form-select">
                                <?php foreach($user AS $val){ ?>
                                    <option value="<?= $val->usr_id; ?>"><?= $val->usr_full; ?> | <?= $val->usr_email; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Level</label>
                            <select name="usg_id" class="form-select">
                                <?php foreach($level AS $val){ ?>
                                    <option value="<?= $val->usg_id; ?>"><?= $val->usg_name; ?> | <?= $val->usg_note; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>