<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
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
            <div class="table-responsive">
                <table class="table dt-responsive nowrap w-100" id="tbdata">
                    <thead>
                        <tr>
                            <th width="20px">No.</th>
                            <th>Layanan</th>
                            <th>Nama</th>
                            <th>Subjek</th>
                            <th>Status Kirim</th>
                            <th>Status Selesai</th>
                            <th>Update</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>'; 

    $('#tbdata').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url('module/it/data'); ?>",
            "type": "POST",
            "data": function(d) {
                d[csrfName] = csrfHash;
            },
            "dataSrc": function(json) {
                csrfHash = json.csrfHash;
                $('input[name="<?= csrf_token(); ?>"]').val(csrfHash);
                return json.data;
            }
        },
        "columns": [
            { "data": "no" },
            { "data": "lay_nama" },
            { "data": "data_nama" },
            { "data": "data_subjek" },
            { "data": "data_status_kirim" },
            { "data": "data_status_selesai" },
            { "data": "data_update" },
            { "data": "aksi", "orderable": false, "searchable": false }
        ],
        "drawCallback": function(settings) {
            lucide.createIcons();
        }
    });

    
    $('#tbdata').on('click', '.btn-lihat', function(e) {
        e.preventDefault(); 
        const IdData = $(this).data('id'); 

        Swal.fire({
            title: 'Sebentar!',
            text: "Apakah anda mau membuka data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, buka!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('module/it/detail-data'); ?>', 
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        kirimID: IdData,
                        [csrfName]: csrfHash 
                    },
                    success: function(response) {
                        if (response['<?= csrf_token() ?>']) {
                            csrfHash = response['<?= csrf_token() ?>'];
                            $('input[name="<?= csrf_token() ?>"]').val(csrfHash);
                        }

                        if (response.status === 'success') {
                            Swal.fire(
                                'Dihapus!',
                                response.message,
                                'success'
                            ).then(() => {
                                // $('#tbdata').DataTable().ajax.reload(null, false); 
                                window.location.href = base_url(response.redirect);
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        let response;
                        try {
                            response = JSON.parse(xhr.responseText);
                            if (response['<?= csrf_token(); ?>']) {
                                csrfHash = response['<?= csrf_token(); ?>'];
                                $('input[name="<?= csrf_token(); ?>"]').val(csrfHash);
                            }
                        } catch (e) {
                            console.error('Gagal parsing response error atau tidak ada CSRF token di response error:', e);
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.'
                        });
                    }
                });
            }
        });
    });
});
</script>