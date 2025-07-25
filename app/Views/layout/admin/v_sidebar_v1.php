<?php 
    $model = new App\Models\AuthModel();
    $usgmod = $model->usgmod(); // Hasil query modul yang diijinkan
    $modallow = array_column($usgmod, 'mod_id');
    $linkallow = array_column($usgmod, 'mod_link');
    session()->set('privmod', $linkallow);
    $modparent = $model->modparent(); // Query untuk mendapatkan modul utama
    foreach ($modparent as $value) {
        $value->modchild = $model->modchild($value->mod_id); // Menambahkan anak modul ke objek parent
    }
?>
<ul class="app-menu">
    <li class="menu-title">Menu</li>
    <?php foreach ($modparent as $parent): ?>
        <?php if (!empty($parent->modchild)): ?>
            <!-- Modul dengan dropdown -->
            <li class="menu-title"><?= $parent->mod_name ?></li>
            <li class="menu-item <?php if (service('uri')->getSegment(1) == $parent->mod_link) echo 'active'; ?>">
                <a href="#<?= $parent->mod_short ?>" data-bs-toggle="collapse" class="menu-link waves-effect">
                    <span class="menu-icon"><i data-lucide="<?= $parent->mod_icon ?>"></i></span>
                    <span class="menu-text"><?= $parent->mod_name ?></span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse <?php if(strpos(service('uri')->getPath(), $parent->mod_link) !== false) echo 'show'; ?>" id="<?= $parent->mod_short ?>">
                    <ul class="sub-menu">
                        <?php foreach ($parent->modchild as $child): ?>
                            <?php if (in_array($child->mod_id, $modallow)): ?>
                                <li class="menu-item <?php if(strpos(service('uri')->getPath(), '/'.$child->mod_link) === 0) echo 'active';?>">
                                    <a href="<?= base_url($child->mod_link) ?>" class="menu-link">
                                        <span class="menu-text"><?= $child->mod_name ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
        <?php elseif (in_array($parent->mod_id, $modallow) && $parent->mod_display == 'Y'): ?>
            <!-- Modul tanpa dropdown -->
            <li class="menu-item <?= strpos(service('uri')->getPath(), $parent->mod_link) === 0 ? 'active' : '' ?>">
                <a href="<?= base_url($parent->mod_link) ?>" class="menu-link waves-effect">
                    <span class="menu-icon"><i data-lucide="<?= $parent->mod_icon ?>"></i></span>
                    <span class="menu-text"><?= $parent->mod_name ?></span>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>