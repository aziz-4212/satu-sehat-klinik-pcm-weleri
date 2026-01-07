<nav class="main-header navbar navbar-expand navbar-teal navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-light" data-widget="pushmenu" href="#" role="button" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="nav-link text-light" title="Dashboard">
                <i class="fas fa-home mr-1"></i>
                Dashboard
            </a>
        </li>
    </ul>

    <!-- Center - App Title for larger screens -->
    <div class="navbar-nav mx-auto d-none d-lg-flex">
        <span class="navbar-text text-light font-weight-bold" style="font-size: 1.1rem;">
            <i class="fas fa-heartbeat mr-2"></i>
            Satu Sehat - Platform Rekam Medis
        </span>
    </div>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link text-light" data-toggle="dropdown" href="#" title="Notifikasi">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item-text">3 Notifikasi Baru</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-sync mr-2 text-info"></i> Data berhasil disinkronkan
                    <span class="float-right text-muted text-sm">2 menit</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-plus mr-2 text-success"></i> Pasien baru terdaftar
                    <span class="float-right text-muted text-sm">5 menit</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-exclamation-triangle mr-2 text-warning"></i> Perlu validasi data
                    <span class="float-right text-muted text-sm">10 menit</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
            </div>
        </li>

        <!-- User Info -->
        <li class="nav-item dropdown">
            <a class="nav-link text-light" data-toggle="dropdown" href="#" title="Profil Pengguna">
                <i class="fas fa-user-circle mr-1"></i>
                <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'User' }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-header bg-light">
                    <strong>{{ auth()->user()->name ?? 'User' }}</strong>
                    <small class="text-muted d-block">Administrator</small>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profil Saya
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog mr-2"></i> Pengaturan
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </a>
            </div>
        </li>

        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link text-light" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
