<?php if ($sweetAlert = session()->getFlashdata('sweetAlert')): ?>
    <div class="flash-data" data-flashmessage="<?= esc($sweetAlert['message']) ?>" data-flashicon="<?= esc($sweetAlert['icon']) ?>"></div>
<?php endif; ?>
<style>
    .btsub { width : 100px; margin-bottom : 3px; }
    .slgrup { margin-bottom: 10px;}
</style>
<div class="container-fluid">
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"><?= $title; ?></h4>
                <p class="text-subtitle text-muted">Halaman <?= $subtitle; ?></p>
                <a href='<?= base_url('manajemen/level-user'); ?>' class="btn btn-success">Kembali</a>
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
                <table id="tblid" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Induk</th>
                            <th>Modul</th>
                            <th>Status</th>
                            <th>Kelola</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    var userGroupId = "<?= $usg_id ?>";
    $(document).ready(function() {
        loadData();
    });

    function loadData() {
        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>'; 
        $('#tblid').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                type: "POST",
                url: "<?= base_url('manajemen/level-user/module_ajax') ?>",
                data: function(d) {
                    d.usg = userGroupId;
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    csrfHash = json.csrfHash;
                    return json.data;
                }
            },
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 }
            ]
        });
    }

    $('tbody').on('click', '.privmod_upd', function() {
        var usg = userGroupId;
        var ops = $(this).data('ops');
        var mod = $(this).attr('id');

        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>'; 

        $.ajax({
            type: "POST",
            url: "<?= base_url('manajemen/level-user/module/privmod_upd') ?>",
            data: {
                "ops": ops,
                "usg": usg,
                "mod": mod,
            },
            success: function(msg) {
                if (msg == 'success') {
                    if (ops == 'add') {
                        $('#'+mod).data('ops', 'del');
                        $('#'+mod).closest('td').prev().html("<center><span class='badge bg-success'>True</span></center>");
                    } else {
                        $('#'+mod).data('ops', 'add');
                        $('#'+mod).closest('td').prev().html("<center><span class='badge bg-warning'>False</span></center>");
                    }
                } else {
                    $('#'+mod).html('error!');
                }
            }
        });
    });
</script>
