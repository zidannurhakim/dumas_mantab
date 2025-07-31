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
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="">
                        <div class="form-group">
                            <select name="lay_id" id="lay_id" class="form-select select2-layanan">
                            </select>
                        </div>
                    </form>
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
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-layanan').select2({
        ajax: {
            url: "<?= base_url('module/admin/data-layanan') ?>",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return {
                    search: params.term
                };
            },
            processResults: function(response) {
                return {
                    results: response.results
                };
            },
            cache: true
        }
    });

    // Lakukan panggilan AJAX awal untuk mendapatkan data pertama untuk Select2
    $.ajax({
        url: "<?= base_url('module/admin/data-layanan') ?>",
        type: "POST",
        dataType: "json",
        data: {
            search: ''
        },
        success: function(response) {
            if (response.results && response.results.length > 0) {
                var firstItem = response.results[0];
                var newOption = new Option(firstItem.text, firstItem.id, true, true);
                $('#lay_id').append(newOption).trigger('change');
            } else {
                console.log("Tidak ada data layanan yang tersedia untuk dipilih secara otomatis.");
                var noDataOption = new Option("Tidak Ada Layanan", "", true, true);
                $('#lay_id').append(noDataOption).trigger('change');
            }
            // Setelah Select2 diisi, picu event kustom
            $('#lay_id').trigger('select2:initial_load_done');
        },
        error: function(xhr, status, error) {
            console.error("Error fetching initial Select2 data:", status, error);
            var errorOption = new Option("Gagal Memuat Layanan", "", true, true);
            $('#lay_id').append(errorOption).trigger('change');
            // Tetap picu event agar DataTables bisa diinisialisasi
            $('#lay_id').trigger('select2:initial_load_done');
        }
    });
});
</script>

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>'; 
    var dataTable;
    $('#lay_id').on('select2:initial_load_done', function() {
        dataTable =$('#tbdata').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('module/admin/data'); ?>",
                "type": "POST",
                "data": function(d) {
                    d[csrfName] = csrfHash;
                    d.lay_id = $('#lay_id').val();
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
        $('#lay_id').on('change', function() {
            if (dataTable) { // Pastikan dataTable sudah diinisialisasi
                dataTable.ajax.reload();
            }
        });
    });
});
</script>