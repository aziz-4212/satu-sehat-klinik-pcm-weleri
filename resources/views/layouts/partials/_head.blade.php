<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Satu Sehat - Platform Rekam Medis Kementerian Kesehatan</title>

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('assets/dist/img/favicon.ico') }}">

<!-- Google Fonts: Inter & Nunito -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
{{-- summernote --}}
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css') }}">


<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">

<!-- Summernotes -->
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css')}}">

<!-- Satu Sehat Custom Theme -->
<link rel="stylesheet" href="{{ asset('assets/css/satu-sehat-theme.css') }}">

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

{{-- Modern Satu Sehat Theme --}}
<style>
    :root {
        --primary-color: #0D6EFD;
        --primary-dark: #0B5ED7;
        --secondary-color: #6C757D;
        --success-color: #198754;
        --info-color: #0DCAF0;
        --warning-color: #FFC107;
        --danger-color: #DC3545;
        --light-color: #F8F9FA;
        --dark-color: #212529;
        --satu-sehat-green: #52C997;
        --satu-sehat-blue: #1B73E8;
        --gradient-primary: linear-gradient(135deg, var(--satu-sehat-blue), var(--satu-sehat-green));
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #F5F7FA;
    }

    /* Modern Brand Styles */
    .brand-link {
        background: var(--gradient-primary) !important;
        border: none !important;
        box-shadow: 0 4px 20px rgba(27, 115, 232, 0.15) !important;
    }

    .brand-text {
        font-family: 'Nunito', sans-serif !important;
        font-weight: 700 !important;
        color: white !important;
        text-shadow: none !important;
    }

    /* Modern Navbar */
    .navbar-teal {
        background: var(--gradient-primary) !important;
        box-shadow: 0 2px 15px rgba(27, 115, 232, 0.1) !important;
    }

    .main-header .nav-link {
        border-radius: 8px !important;
        margin: 0 2px !important;
        transition: all 0.3s ease !important;
    }

    .main-header .nav-link:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        transform: translateY(-1px) !important;
    }

    /* Modern Sidebar */
    .main-sidebar {
        background: #FFFFFF !important;
        box-shadow: 4px 0 30px rgba(0, 0, 0, 0.08) !important;
    }

    .sidebar-light-teal .nav-sidebar .nav-item > .nav-link {
        color: #4A5568 !important;
        border-radius: 12px !important;
        margin: 2px 8px !important;
        padding: 12px 16px !important;
        transition: all 0.3s ease !important;
        font-weight: 500 !important;
    }

    .sidebar-light-teal .nav-sidebar .nav-item > .nav-link:hover {
        background: rgba(27, 115, 232, 0.08) !important;
        color: var(--satu-sehat-blue) !important;
        transform: translateX(4px) !important;
    }

    .sidebar-light-teal .nav-sidebar .nav-item > .nav-link.active {
        background: var(--gradient-primary) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(27, 115, 232, 0.2) !important;
    }

    .sidebar-light-teal .nav-sidebar .nav-item > .nav-link.active .nav-icon {
        color: white !important;
    }

    /* Modern Cards */
    .card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06) !important;
        transition: all 0.3s ease !important;
    }

    .card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12) !important;
    }

    .card-header {
        background: transparent !important;
        border-bottom: 1px solid #E2E8F0 !important;
        padding: 20px !important;
        border-radius: 16px 16px 0 0 !important;
    }

    .card-title {
        font-family: 'Nunito', sans-serif !important;
        font-weight: 600 !important;
        color: var(--dark-color) !important;
        margin: 0 !important;
    }

    /* Modern Buttons */
    .btn {
        border-radius: 10px !important;
        font-weight: 500 !important;
        padding: 10px 20px !important;
        transition: all 0.3s ease !important;
    }

    .btn-primary {
        background: var(--gradient-primary) !important;
        border: none !important;
        box-shadow: 0 4px 15px rgba(27, 115, 232, 0.2) !important;
    }

    .btn-primary:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 25px rgba(27, 115, 232, 0.3) !important;
    }

    .btn-success {
        background: linear-gradient(135deg, #198754, #52C997) !important;
        border: none !important;
    }

    /* Modern Form Controls */
    .form-control {
        border-radius: 10px !important;
        border: 2px solid #E2E8F0 !important;
        padding: 12px 16px !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus {
        border-color: var(--satu-sehat-blue) !important;
        box-shadow: 0 0 0 0.2rem rgba(27, 115, 232, 0.1) !important;
    }

    /* Content Wrapper */
    .content-wrapper {
        background: #F5F7FA !important;
        padding: 20px !important;
    }

    /* User Panel Modern */
    .user-panel {
        border-bottom: 1px solid #E2E8F0 !important;
        padding: 20px 16px !important;
    }

    .user-panel .info a {
        color: var(--dark-color) !important;
        font-weight: 600 !important;
    }

    /* Modern DataTable */
    .table {
        border-radius: 12px !important;
        overflow: hidden !important;
    }

    .table thead th {
        background: var(--gradient-primary) !important;
        color: white !important;
        font-weight: 600 !important;
        border: none !important;
    }

    /* Overlay Modern */
    .overlay {
        backdrop-filter: blur(8px) !important;
        background: rgba(255, 255, 255, 0.8) !important;
    }

    .overlay i {
        color: var(--satu-sehat-blue) !important;
    }

    /* Modern Stats Cards */
    .info-box {
        border-radius: 16px !important;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06) !important;
        border: none !important;
        transition: all 0.3s ease !important;
    }

    .info-box:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12) !important;
    }

    .info-box-icon {
        border-radius: 16px !important;
    }

    /* Custom Satu Sehat Colors */
    .bg-satu-sehat-gradient {
        background: var(--gradient-primary) !important;
    }

    .text-satu-sehat-blue {
        color: var(--satu-sehat-blue) !important;
    }

    .text-satu-sehat-green {
        color: var(--satu-sehat-green) !important;
    }

    /* Modern Footer */
    .main-footer {
        background: white !important;
        border-top: 1px solid #E2E8F0 !important;
        color: var(--secondary-color) !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content-wrapper {
            padding: 10px !important;
        }

        .card {
            margin-bottom: 20px !important;
        }
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .slide-in-left {
        animation: slideInLeft 0.5s ease-out;
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>

<!-- Modal backdrop: ensure it covers entire viewport and is transparent (no dark overlay) -->
<style>
    /* put modal above backdrop */
    .modal {
        z-index: 20060 !important;
    }

    /* backdrop covers full viewport but transparent */
    .modal-backdrop {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: rgba(0,0,0,0) !important; /* transparent */
        z-index: 20050 !important;
    }

    .modal-backdrop.show {
        opacity: 1 !important;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}" />
