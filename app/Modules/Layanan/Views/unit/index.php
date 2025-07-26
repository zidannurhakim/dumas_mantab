<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
                <a href='<?= base_url('layanan/unit/tambah'); ?>' class="btn btn-success">Buat Baru</a>
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
                            <th>Nama</th>
                            <th>Status</th>
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
    <div class="card">
        <div class="card-body">
            <h5 class="text-center"><b>Struktur Unit MAN 3 Banyuwangi</b></h5>
            <hr>
            <div class="accordion accordion-flush border border-secondary" id="detStruktur">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="detSOI">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#detStrukturOne" aria-expanded="false" aria-controls="detStrukturOne">
                            <i data-lucide="book"></i>&nbsp; Detail 
                        </button>
                    </h2>
                    <div id="detStrukturOne" class="accordion-collapse collapse" aria-labelledby="detSOI"
                        data-bs-parent="#detStruktur">
                        <div class="accordion-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dd" id="nestable">
                                        <ol class="dd-list"></ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="<?= base_url('assets/admin/nestable/jquery.nestable.css')?>">
<script src="<?= base_url('assets/admin/nestable/jquery.nestable.js')?>"></script>
<script>
    $(document).ready(function () {
        $.getJSON("<?= base_url('layanan/unit/data-chart'); ?>", function (data) {
            // console.log(data);
            function buildNestable(items) {
                let html = '<ol class="dd-list">';
                items.forEach(item => {
                    html += `<li class="dd-item" data-id="${item.id}">
                                <div class="dd-handle">${item.name}</div>`;
                    if (item.children && item.children.length > 0) {
                        html += buildNestable(item.children);
                    }
                    html += '</li>';
                });
                html += '</ol>';
                return html;
            }

            $('#nestable .dd-list').html(buildNestable(data));
        });
    });
</script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>'; 

    $('#tbdata').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url('layanan/unit/data'); ?>",
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
            { "data": "unit_nama" },
            { "data": "unit_status" },
            { "data": "unit_update" },
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
                    url: '<?= base_url('layanan/jenis/hapus'); ?>', 
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