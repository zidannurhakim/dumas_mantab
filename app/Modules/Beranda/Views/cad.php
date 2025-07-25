    
        <?php foreach($total_arsip AS $row){ ?>
        <div class="col-md-3">
            <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary text-white" style="width: 50px; height: 50px;">
                    <i data-lucide="archive" class="fs-4"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-1 text-black"><b>Total Arsip Aktif</b></h4>
                    <h5 class="mb-0 text-black"><?= $row->Total; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-success text-white" style="width: 50px; height: 50px;">
                    <i data-lucide="file-text" class="fs-4"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-1 text-black"><b>Total Arsip</b></h4>
                    <h5 class="mb-0 text-black"><?= $row->Naskah; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-success text-white" style="width: 50px; height: 50px;">
                    <i data-lucide="file-text" class="fs-4"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-1 text-black"><b>Total Disposisi</b></h4>
                    <h5 class="mb-0 text-black"><?= $row->Disposisi; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-success text-white" style="width: 50px; height: 50px;">
                    <i data-lucide="file-text" class="fs-4"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-1 text-black"><b>Total Tembusan</b></h4>
                    <h5 class="mb-0 text-black"><?= $row->Tembusan; ?></h5>
                </div>
            </div>
        </div>
        <?php } ?>
        <div><br></div>
        <div class="col-md-6">
            <div class="card card-body">
                <h4 class="text-center text-muted"><b>Naskah Keluar</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="send" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Dikirim</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-danger text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="delete" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Ditolak</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php foreach($total_naskahmasuk AS $row){ ?>
        <div class="col-md-6">
            <div class="card card-body">
                <h4 class="text-center text-muted"><b>Naskah Masuk</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="folder-minus" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Dibaca</b></h5>
                                <h5 class="mb-0 text-black"><?= $row->total_baca; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-secondary text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="arrow-up-right" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Ditindaklanjuti</b></h5>
                                <h5 class="mb-0 text-black"><?= $row->total_tindaklanjut; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <!-- <div class="col-md-12">
            <div class="card card-body">
                <h4 class="text-center text-muted"><b>Tandatangan Naskah</b></h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-danger text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="x-square" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Ditolak</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-secondary text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="user-check" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Diperbaiki</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="edit-2" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Ditandatangani</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-secondary text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="send" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Dikirim</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="col-md-12">
            <div class="card card-body">
                <h4 class="text-center text-muted"><b>Verifikasi Naskah</b></h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="users" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Diverifikasi</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-danger text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="user-x" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Ditolak</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-secondary text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="user-check" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Diperbaiki</b></h5>
                                <h5 class="mb-0 text-black">0</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <?php foreach($total_disposisi AS $row){ ?>
        <div class="col-md-6">
            <div class="card card-body">
                <h4 class="text-center text-muted"><b>Naskah Disposisi</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="folder-minus" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Dibaca</b></h5>
                                <h5 class="mb-0 text-black"><?= $row->total_baca; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-secondary text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="arrow-up-right" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Ditindaklanjuti</b></h5>
                                <h5 class="mb-0 text-black"><?= $row->total_tindaklanjut; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php foreach($total_tembusan AS $row){ ?>
        <div class="col-md-6">
            <div class="card card-body">
                <h4 class="text-center text-muted"><b>Naskah Tembusan</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-warning text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="folder-minus" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Dibaca</b></h5>
                                <h5 class="mb-0 text-black"><?= $row->total_baca; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 shadow-sm rounded bg-white">
                            <div class="d-flex justify-content-center align-items-center rounded-circle bg-secondary text-white" style="width: 50px; height: 50px;">
                                <i data-lucide="arrow-up-right" class="fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 text-black"><b>Belum Ditindaklanjuti</b></h5>
                                <h5 class="mb-0 text-black"><?= $row->total_tindaklanjut; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>