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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            lucide.createIcons();  // Refresh lucide icons setiap kali data table selesai menampilkan data
        }
    });
});
</script>