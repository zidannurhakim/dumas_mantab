<div class="topbar">
    <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

        <div class="logo-box">
            <a href="<?= base_url('beranda'); ?>" class="logo-light">
                <img src="<?= base_url('assets/other/logo.png') ?>" alt="logo" class="logo-lg" height="32">
                <img src="<?= base_url('assets/other/logo.png') ?>" alt="small logo" class="logo-sm" height="32">
            </a>

            <a href="<?= base_url('beranda'); ?>" class="logo-dark">
                <img src="<?= base_url('assets/other/logo.png') ?>" alt="dark logo" class="logo-lg" height="32">
                <img src="<?= base_url('assets/other/logo.png') ?>" alt="small logo" class="logo-sm" height="32">
            </a>
        </div>

        <button class="button-toggle-menu waves-effect waves-light rounded-circle">
            <i class="mdi mdi-menu"></i>
        </button>
    </div>

    <ul class="topbar-menu d-flex align-items-center gap-2">

        <li class="d-none d-md-inline-block">
            <a class="nav-link waves-effect waves-light" href="#" data-bs-toggle="fullscreen">
                <i class="mdi mdi-fullscreen font-size-24"></i>
            </a>
        </li>
        <li class="nav-link waves-effect waves-light" id="theme-mode">
            <i class="bx bx-moon font-size-24"></i>
        </li>
        <li class="dropdown">
            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="<?= session()->get('usr_profile'); ?>" class="rounded-circle" style="width: 40px; height: 40px;">
                <span class="ms-1 d-none d-md-inline-block">
                    <?= session()->get('usr_full'); ?> <i class="mdi mdi-chevron-down"></i> <br>
                    <span class="badge bg-danger"><?= session()->get('usg_name'); ?></span>
                </span>
            </a>


            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0" id="greeting"></h6>
                </div>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('refresh-session'); ?>" class="dropdown-item notify-item">
                    <i data-lucide="repeat" class="font-size-16 me-2"></i>
                    <span>Refresh Session</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('logout'); ?>" id="logoutButton" class="dropdown-item notify-item">
                    <i data-lucide="log-out" class="font-size-16 me-2"></i>
                    <span>Logout</span>
                </a>
            </div>
        </li>
    </ul>
</div>
<script>
    function updateGreeting() {
        // Set timezone to Jakarta (WIB)
        const now = new Date().toLocaleString("en-US", { timeZone: "Asia/Jakarta" });
        const currentDate = new Date(now);
        const hours = currentDate.getHours();
        const minutes = currentDate.getMinutes();
        const currentTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
        let greeting;

        if (hours >= 4 && hours <= 10) {
            greeting = "Selamat Pagi, Jangan Lupa Tersenyum 😁";
        } else if (hours >= 11 && hours <= 14) {
            greeting = "Selamat Siang, Jaga Kesehatan Selalu 😇";
        } else if (hours >= 15 && hours <= 17) {
            greeting = "Selamat Sore, Manage Waktu Dengan Baik Ya 😋";
        } else if (hours >= 18 && hours <= 23) {
            greeting = "Selamat Malam, Beri Waktu Untuk Keluarga 🥰";
        } else {
            greeting = `Waktu ${currentTime} Dini Hari, Jangan Lupa Istirahat 🥹`;
        }

        document.getElementById("greeting").textContent = greeting;
    }

    // Update greeting once per minute
    setInterval(updateGreeting, 60000); // 60000 ms = 1 minute

    // Call once immediately to set the initial greeting
    updateGreeting();
</script>
<script>
    document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault();

        // Menampilkan alert window
        var confirmation = confirm('Anda yakin ingin logout?');

        // Jika pengguna mengklik "OK", lakukan logout
        if (confirmation) {
            window.location.href = this.getAttribute('href');
        }
    });
</script>