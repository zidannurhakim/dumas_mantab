<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
                <a href='<?= base_url('manajemen/user/tambah'); ?>' class="btn btn-success">Buat Baru</a>
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
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th>Status</th>
                            <th>Update</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
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
            "url": "<?php echo base_url('manajemen/user/data'); ?>",
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
            { "data": "usr_email" },
            { "data": "usr_full" },
            { "data": "usr_active" },
            { "data": "usr_update" },
            { "data": "aksi", "orderable": false, "searchable": false }
        ],
        "drawCallback": function(settings) {
            lucide.createIcons();  
        }
    });

    $('#tbdata').on('click', '.btn-delete', function(e) {
        e.preventDefault(); 
        const IdData = $(this).data('id'); 

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('manajemen/user/hapus'); ?>', 
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        kirimID: IdData,
                        [csrfName]: csrfHash 
                    },
                    success: function(response) {
                        // Update CSRF token dari response
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
                                $('#tbdata').DataTable().ajax.reload(null, false); 
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