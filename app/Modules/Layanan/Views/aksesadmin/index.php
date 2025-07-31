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
                            <th>Nama</th>
                            <th>Note/Catatan</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/admin/jquery-3.6.0.min.js'); ?>"></script>
<script>
$(document).ready(function() {
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>'; 
    $('#tbdata').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url('layanan/akses-admin/data'); ?>",
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
            { "data": "usg_name" },
            { "data": "usg_note" },
            { "data": "aksi", "orderable": false, "searchable": false }
        ],
        "drawCallback": function(settings) {
            lucide.createIcons();
        }
    });
});
</script>