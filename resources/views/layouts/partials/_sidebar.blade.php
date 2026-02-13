<aside class="main-sidebar sidebar-light-teal elevation-4" style="height: 1000vh;">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link navbar-teal">
        <div class="brand-image" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); border-radius: 8px; margin-right: 10px;">
            <i class="fas fa-heartbeat" style="color: white; font-size: 18px;"></i>
        </div>
        <span class="brand-text font-weight-bold text-light">Satu Sehat</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/avaspk.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="" class="d-block">{{auth()->user()->name}}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column slide-in-left" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- MAIN NAVIGATION -->
                <li class="nav-header text-uppercase" style="font-size: 11px; font-weight: 600; color: #718096; margin-top: 10px;">
                    <i class="fas fa-home mr-2"></i>Navigasi Utama
                </li>

                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link{{ request()->is(['/']) ? ' active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie text-primary"></i>
                        <p>Dashboard Utama</p>
                    </a>
                </li>

                <!-- DASHBOARD SECTIONS -->
                <li class="nav-header text-uppercase" style="font-size: 11px; font-weight: 600; color: #718096; margin-top: 15px;">
                    <i class="fas fa-hospital mr-2"></i>Dashboard Layanan
                </li>

                <li class="nav-item">
                    <a href="{{route('dashboard.rawat-jalan')}}" class="nav-link{{ request()->is(['dashboard-rawat-jalan']) ? ' active' : '' }}">
                        <i class="nav-icon fas fa-walking text-info"></i>
                        <p>Rawat Jalan</p>
                        <span class="badge badge-info right">New</span>
                    </a>
                </li>

                <li class="nav-header text-uppercase" style="font-size: 11px; font-weight: 600; color: #718096; margin-top: 15px;">
                    <i class="fas fa-concierge-bell mr-2"></i>Jenis Pelayanan
                </li>

                <li class="nav-item">
                    <a href="{{route('rawat-jalan.menu')}}" class="nav-link{{ request()->is(['rawat-jalan/menu', 'rawat-jalan/menu/*']) ? ' active' : '' }}">
                        <i class="nav-icon fas fa-walking text-info"></i>
                        <p>Rawat Jalan</p>
                    </a>
                </li>

                <!-- KYC SECTION -->
                @if ($user = auth()->user()->practioner)
                    <li class="nav-header text-uppercase" style="font-size: 11px; font-weight: 600; color: #718096; margin-top: 15px;">
                        <i class="fas fa-shield-alt mr-2"></i>Verifikasi
                    </li>

                    <li class="nav-item">
                        <a href="{{route('dashboard.kyc')}}" class="nav-link{{ request()->is(['kyc']) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-user-check text-success"></i>
                            <p>KYC Verifikasi</p>
                        </a>
                    </li>
                @endif
                @if ($user = auth()->user()->name == 'admin')
                    <!-- MASTER DATA & RESOURCES -->
                    <li class="nav-header text-uppercase" style="font-size: 11px; font-weight: 600; color: #718096; margin-top: 15px;">
                        <i class="fas fa-database mr-2"></i>Master Data & Resource
                    </li>

                    <li class="nav-item{{ request()->is(['patient', 'patient/*', 'pasien-nik-tidak-terdaftar', 'pasien-nik-tidak-terdaftar/*', 'practitioner', 'practitioner/*', 'organization', 'organization/*', 'location', 'location/*', 'master-mapmr-loinc', 'master-mapmr-loinc/*', 'master-bentuk-obat', 'master-bentuk-obat/*']) ? ' menu-open' : '' }}">
                        <a href="#" class="nav-link{{ request()->is(['patient', 'patient/*', 'pasien-nik-tidak-terdaftar', 'pasien-nik-tidak-terdaftar/*', 'practitioner', 'practitioner/*', 'organization', 'organization/*', 'location', 'location/*', 'master-mapmr-loinc', 'master-mapmr-loinc/*', 'master-bentuk-obat', 'master-bentuk-obat/*']) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-sitemap text-info"></i>
                            <p>Resource Management<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('patient.index') }}"
                                    class="nav-link{{ request()->is(['patient', 'patient/*']) ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon text-primary"></i>
                                    <p>Data Pasien</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('practitioner.index') }}"
                                    class="nav-link{{ request()->is(['practitioner', 'practitioner/*']) ? ' active' : '' }}">
                                    <i class="far fa-circle nav-icon text-success"></i>
                                    <p>Data Practitioner</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- SYSTEM CONFIGURATION -->
                    <li class="nav-header text-uppercase" style="font-size: 11px; font-weight: 600; color: #718096; margin-top: 15px;">
                        <i class="fas fa-cog mr-2"></i>Sistem
                    </li>

                    <li class="nav-item">
                        <a href="{{route('config.index')}}" class="nav-link{{ request()->is(['config']) ? ' active' : '' }}">
                            <i class="nav-icon fas fa-sliders-h text-secondary"></i>
                            <p>Konfigurasi</p>
                            <span class="badge badge-secondary right">Admin</span>
                        </a>
                    </li>
                @endif

                <!-- QUICK ACTIONS -->
                <li class="nav-header text-uppercase" style="font-size: 11px; font-weight: 600; color: #718096; margin-top: 20px;">
                    <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="syncData()">
                        <i class="nav-icon fas fa-sync text-primary"></i>
                        <p>Sinkronisasi Data</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="generateReport()">
                        <i class="nav-icon fas fa-file-export text-info"></i>
                        <p>Generate Laporan</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
