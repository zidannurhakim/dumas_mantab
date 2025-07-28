<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
                <a href='<?= base_url('manajemen/user'); ?>' class="btn btn-success">Kembali</a>
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
            <?php foreach ($user AS $val){ ?>
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Nama Lengkap <code class="text-danger">*</code></label>
                            <input type="text" name="usr_full" class="form-control" value="<?= $val->usr_full; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal Lahir <code class="text-danger">*</code></label>
                            <input type="date" name="usr_tanggallahir" class="form-control" value="<?= $val->usr_tanggallahir; ?>">
                        </div>
                    </div>
                    <p></p>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Email <code class="text-danger">*</code></label>
                            <input type="email" name="usr_email" class="form-control" value="<?= $val->usr_email; ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>NIP <code class="text-danger">*</code></label>
                            <input type="email" name="usr_nip" class="form-control" value="<?= $val->usr_nip; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Jenis Kelamin <code class="text-danger">*</code></label>
                            <select name="usr_kelamin" class="form-select">
                                <option value="L" <?= ($val->usr_kelamin == 'L')? 'selected':'' ?>>Laki-laki</option>
                                <option value="P" <?= ($val->usr_kelamin == 'P')? 'selected':'' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <p></p>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nomor HP <code class="text-danger">*</code></label>
                            <input type="text" name="usr_nomorhp" class="form-control" value="<?= $val->usr_nomorhp; ?>">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Alamat <code class="text-danger">*</code></label>
                            <textarea type="text" name="usr_alamat" class="form-control" rows="1"><?= $val->usr_alamat; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="usr_active" class="form-select" required>
                                <option value="Y" <?= ($val->usr_active == 'Y')? 'selected':'' ?>>Aktif</option>
                                <option value="N" <?= ($val->usr_active == 'N')? 'selected':'' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <p></p>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" id="submitForm" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        function updateCsrfToken(newToken) {
            $('input[name="<?= csrf_token() ?>"]').val(newToken);
        }
        $('#submitForm').click(function(e) {
            e.preventDefault(); 

            var form = $('#uploadForm')[0];
            var formData = new FormData(form); 
            $.ajax({
                url: '<?= base_url('manajemen/user/'.$usr_id.'/proses-edit') ?>',
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function(response) {
                    // Update CSRF token dari response sukses
                    if (response['<?= csrf_token() ?>']) {
                        updateCsrfToken(response['<?= csrf_token() ?>']);
                    }

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            window.location.href = '<?= base_url('manajemen/user'); ?>';
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