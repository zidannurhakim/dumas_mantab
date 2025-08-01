<h1 class="h3 mb-1"><b>Cek Data Anda</b></h1>

<p class="text-muted mb-1">Portal Layanan Pengajuan dan Pengaduan</p>
<a href="" class="btn btn-danger btn-login btn-sm mb-4">Lihat Panduan Cek Data</a>

<div class="card">
    <div class="card-body">
        <form id="cekData" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
            <div class="col-md-12 mb-2">
                <div class="form-group">
                    <label>ID Data</label>
                    <input type="text" name="data_id" class="form-control" placeholder="Masukkan ID Data...">
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="data_email" class="form-control" placeholder="Masukkan Email..."> 
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <div class="form-group">
                    <label>NIS/NIP</label>
                    <input type="text" name="data_nip" class="form-control" placeholder="Masukkan NIS/NIP..."> 
                </div>
            </div>
            <div class="col-md-12 d-grid">
                <button class="btn btn-primary btn-sm" id="submitCekData">Cek Data</button>
            </div>
        </form>
    </div>
</div>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        function updateCsrfToken(newToken) {
            $('input[name="<?= csrf_token() ?>"]').val(newToken);
        }
        $('#submitCekData').click(function(e) {
            e.preventDefault(); 

            var form = $('#cekData')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: 'Apakah Datanya Sudah Benar?',
                text: "Data akan diproses",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Cek Data!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('cek-data/proses-pengecekan') ?>',
                        type: 'POST',
                        data: formData,
                        processData: false, 
                        contentType: false, 
                        dataType: 'json',
                        success: function(response) {
                            if (response['<?= csrf_token() ?>']) {
                                updateCsrfToken(response['<?= csrf_token() ?>']);
                            }

                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then(() => {
                                    window.location.href = `<?= base_url('cek-data/') ?>${response.data_id}/detail-data`;
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
                }
            });
        });
    });
</script>