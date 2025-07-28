<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
                <a href='<?= base_url('layanan/unit'); ?>' class="btn btn-success">Kembali</a>
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
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="unit_nama" required>
                                </div>
                            </div>
                            <p></p>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Induk</label>
                                    <select name="unit_unitid" id="unit_unitid" class="form-select select2-unit">
                                        <option value="KOSONG">KOSONG (Apabila Merupakan Induk Utama)</option>
                                    </select>
                                </div>
                            </div>
                            <p></p>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status <code class="text-danger">*</code></label>
                                    <select class="form-select" name="unit_status" required>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <p></p>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" id="submitForm" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        function updateCsrfToken(newToken) {
            $('input[name="<?= csrf_token() ?>"]').val(newToken);
        }

        $('.select2-unit').select2({
            placeholder: "Pilih Nama Yang Akan Ditambahkan",
            allowClear: true,
            ajax: {
                url: "<?= base_url('layanan/unit/data-unit') ?>",
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
                url: '<?= base_url('layanan/unit/tambah/proses-tambah') ?>',
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
                            window.location.href = '<?= base_url('layanan/unit/tambah'); ?>';
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