<h1 class="h3 mb-1"><b>Selamat Datang di <?= env('APPNAME'); ?></b></h1>

<p class="text-muted mb-1">Portal Layanan Pengajuan dan Pengaduan</p>
<a href="" class="btn btn-danger btn-login btn-sm d-block mb-4">Lihat Panduan</a>

<form id="uploadForm" enctype="multipart/form-data">
    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label>Jenis Layanan <code class="text-danger">*</code></label>
                <select name="lay_id" id="lay_id" class="form-select select2-layanan">
                </select>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label>Metode <code class="text-danger">*</code></label>
                <select name="data_metode" class="form-select">
                    <option value="PENGAJUAN">PENGAJUAN</option>
                    <option value="PENGADUAN">PENGADUAN</option>
                </select>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label>Nama <code class="text-danger">*</code></label>
                <input type="text" class="form-control" name="data_nama">
            </div>
        </div>
        <div class="col-md-8 mb-2">
            <div class="form-group">
                <label>Email <code class="text-danger">*</code></label>
                <input type="email" class="form-control" name="data_email">
                <code class="text-danger">Wajib Menggunakan Email Aktif</code>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <label>Nomor WhatsApp <code class="text-danger">*</code></label>
                <input type="text" class="form-control" name="data_nomorhp">
                <code class="text-danger">Nomor HP Wajib Diawali +62</code>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <label>Peran <code class="text-danger">*</code></label>
                <select name="data_peran" class="form-select">
                    <option value="SISWA">SISWA</option>
                    <option value="ALUMNI">ALUMNI</option>
                    <option value="PEGAWAI">PEGAWAI</option>
                </select>
            </div>
        </div>
        <div class="col-md-8 mb-2">
            <div class="form-group">
                <label>NIS/NIP/NIPT <code class="text-danger">*</code></label>
                <input type="text" class="form-control" name="data_nip">
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label>Subjek <code class="text-danger">*</code></label>
                <input type="text" class="form-control" name="data_subjek">
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label>Pesan <code class="text-danger">*</code></label>
                <textarea name="data_pesan" rows="4" class="form-control"></textarea>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label>Lampiran</label>
                <input type="file" class="form-control" name="data_lampiran">
            </div>
        </div>
        <p></p>
        <div class="col-12 d-flex justify-content-end">
            <button type="submit" id="submitForm" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
<hr>
<div class="text-center mt-4">
    <h5 class="text-muted font-size-16">Login Panel Admin</h5>

    <ul class="list-inline mt-3 mb-0">
        <li class="list-inline-item">
            <a href="<?= $link; ?>" class="btn btn-primary btn-login"><i class="mdi mdi-google"></i> Sign in with Google</a>
        </li>
    </ul>
</div>

<div class="row mt-4">
    <div class="col-12 text-center">
        <p class="text-muted mb-0">Memiliki Masalah Login?
            <a class="text-muted font-weight-medium ms-1" href="mailto:mavic.man3banyuwangi.id"><b>Email Kami</b></a></p>
    </div>
</div>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        function updateCsrfToken(newToken) {
            $('input[name="<?= csrf_token() ?>"]').val(newToken);
        }

        $('.select2-layanan').select2({
            placeholder: "Pilih Jenis Layanan Yang Sesuai",
            allowClear: true,
            ajax: {
                url: "<?= base_url('data-jenis-layanan') ?>",
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
                url: '<?= base_url('layanan/proses-tambah') ?>',
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
                            window.location.href = '<?= base_url('/'); ?>';
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