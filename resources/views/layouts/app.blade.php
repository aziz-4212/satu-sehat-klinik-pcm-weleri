<!DOCTYPE html>
<html lang="id" class="h-100">

<head>
    @include('layouts.partials._head')

    <!-- Satu Sehat Theme Enhancements -->
    <style>
        .wrapper {
            overflow-x: hidden;
        }

        /* Loading Animation */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1B73E8, #52C997);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            transition: opacity 0.5s ease;
        }

        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-logo {
            color: white;
            font-size: 3rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        .loader-text {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-left: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Force sidebar to always be expanded */
        .main-sidebar {
            margin-left: 0 !important;
            transform: translateX(0) !important;
        }

        .content-wrapper {
            margin-left: 250px !important;
        }

        /* Prevent sidebar collapse behavior */
        body.sidebar-collapse .main-sidebar {
            margin-left: 0 !important;
            transform: translateX(0) !important;
        }

        body.sidebar-collapse .content-wrapper {
            margin-left: 250px !important;
        }

    </style>
</head>

<body class="layout-fixed {{ request()->routeIs('resume-medis-rawat-jalan.*') ? 'sidebar-collapse' : '' }}">
    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-logo">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="loader-text">SATU SEHAT</div>
        <div class="loader-text" style="font-size: 0.9rem; font-weight: 400; margin-bottom: 40px;">
            Platform Rekam Medis Kementerian Kesehatan
        </div>
        <div class="loader-spinner"></div>
    </div>

    <div class="wrapper fade-in">
        @include('layouts.partials._navbar')
        @include('layouts.partials._sidebar')

        {{-- Modern Overlay Loading --}}
        {{-- <div class="overlay hidden" id="loadingOverlay">
            <div class="d-flex flex-column align-items-center">
                <div class="loader-spinner mb-3"></div>
                <span class="text-primary font-weight-bold">Memproses data...</span>
            </div>
        </div> --}}

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

        {{-- @include('layouts.partials._footer') --}}
    </div>

    @include('layouts.partials._script')

    {{-- Allow per-view scripts pushed with @push('scripts') --}}
    @stack('scripts')

    <!-- Initialize Modern Theme -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide page loader
            setTimeout(function() {
                document.getElementById('pageLoader').classList.add('hidden');
            }, 100);

            // Add smooth animations to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.classList.add('fade-in');
            });
        });

        // Global functions for quick actions
        function syncData() {
            showLoading();
            // Add your sync logic here
            setTimeout(function() {
                hideLoading();
                toastr.success('Data berhasil disinkronkan dengan Satu Sehat!');
            }, 2000);
        }

        function generateReport() {
            showLoading();
            // Add your report generation logic here
            setTimeout(function() {
                hideLoading();
                toastr.info('Laporan sedang diproses. Anda akan mendapat notifikasi setelah selesai.');
            }, 1500);
        }

        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Enhanced error handling
        window.addEventListener('error', function(e) {
            console.error('JavaScript Error:', e.error);
            toastr.error('Terjadi kesalahan pada aplikasi. Silakan refresh halaman.');
        });
    </script>
</body>
<script>
    // Zoom out jadi 90%
    document.body.style.zoom = "85%";
</script>
</html>
