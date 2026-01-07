@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-2 text-gray-800">
                    <i class="fas fa-users text-primary mr-2"></i>
                    Manajemen Data Pasien
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-primary text-decoration-none">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Data Pasien</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="btn btn-primary btn-modern" data-toggle="modal" data-target="#addPatientModal">
                    <i class="fas fa-plus mr-2"></i>Tambah Pasien Baru
                </button>
                <button class="btn btn-success btn-modern ml-2" onclick="syncData()">
                    <i class="fas fa-sync mr-2"></i>Sinkronkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="widget-stat">
            <div class="d-flex align-items-center">
                <div class="widget-stat-icon" style="background: linear-gradient(135deg, #1B73E8, #4285f4);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-3">
                    <div class="widget-stat-number" id="total-patients">1,234</div>
                    <div class="widget-stat-label">Total Pasien</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="widget-stat">
            <div class="d-flex align-items-center">
                <div class="widget-stat-icon" style="background: linear-gradient(135deg, #52C997, #48bb78);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <div class="widget-stat-number" id="verified-patients">987</div>
                    <div class="widget-stat-label">Terverifikasi NIK</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="widget-stat">
            <div class="d-flex align-items-center">
                <div class="widget-stat-icon" style="background: linear-gradient(135deg, #FFC107, #ffca28);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ml-3">
                    <div class="widget-stat-number">45</div>
                    <div class="widget-stat-label">Pending Verifikasi</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="widget-stat">
            <div class="d-flex align-items-center">
                <div class="widget-stat-icon" style="background: linear-gradient(135deg, #17a2b8, #20c997);">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="ml-3">
                    <div class="widget-stat-number">28</div>
                    <div class="widget-stat-label">Registrasi Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-2"></i>Filter & Pencarian
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterStatus">Status Verifikasi</label>
                            <select class="form-control select2" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterGender">Jenis Kelamin</label>
                            <select class="form-control select2" id="filterGender">
                                <option value="">Semua</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterDate">Tanggal Registrasi</label>
                            <input type="date" class="form-control" id="filterDate">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="searchPatient">Cari Pasien</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchPatient" placeholder="Nama, NIK, No. MR...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Patient Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-table mr-2"></i>Daftar Pasien
                </h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportData('excel')">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportData('pdf')">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="printTable()">
                            <i class="fas fa-print mr-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="patientTable" class="table table-bordered table-striped table-modern">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">No. MR</th>
                                <th width="20%">Nama Lengkap</th>
                                <th width="15%">NIK</th>
                                <th width="10%">Jenis Kelamin</th>
                                <th width="15%">Tanggal Lahir</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Data -->
                            <tr>
                                <td>1</td>
                                <td><span class="badge badge-info">MR001234</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px;">
                                            JD
                                        </div>
                                        <div>
                                            <strong>John Doe</strong>
                                            <br><small class="text-muted">john.doe@email.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>1234567890123456</td>
                                <td>
                                    <i class="fas fa-mars text-primary mr-1"></i>Laki-laki
                                </td>
                                <td>15 Januari 1990</td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check mr-1"></i>Terverifikasi
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" title="Sinkronkan">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><span class="badge badge-info">MR001235</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-pink text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; background-color: #e91e63;">
                                            JS
                                        </div>
                                        <div>
                                            <strong>Jane Smith</strong>
                                            <br><small class="text-muted">jane.smith@email.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>1234567890123457</td>
                                <td>
                                    <i class="fas fa-venus text-pink mr-1"></i>Perempuan
                                </td>
                                <td>22 Maret 1985</td>
                                <td>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary" title="Verifikasi">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><span class="badge badge-info">MR001236</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px;">
                                            AB
                                        </div>
                                        <div>
                                            <strong>Ahmad Budiman</strong>
                                            <br><small class="text-muted">ahmad.budiman@email.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>1234567890123458</td>
                                <td>
                                    <i class="fas fa-mars text-primary mr-1"></i>Laki-laki
                                </td>
                                <td>08 Juli 1978</td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check mr-1"></i>Terverifikasi
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" title="Sinkronkan">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Pasien Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addPatientForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patientName">Nama Lengkap *</label>
                                <input type="text" class="form-control" id="patientName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patientNIK">NIK *</label>
                                <input type="text" class="form-control" id="patientNIK" maxlength="16" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="patientGender">Jenis Kelamin *</label>
                                <select class="form-control select2" id="patientGender" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="patientBirthDate">Tanggal Lahir *</label>
                                <input type="date" class="form-control" id="patientBirthDate" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="patientPhone">No. Telepon</label>
                                <input type="text" class="form-control" id="patientPhone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patientEmail">Email</label>
                                <input type="email" class="form-control" id="patientEmail">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patientBloodType">Golongan Darah</label>
                                <select class="form-control select2" id="patientBloodType">
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="patientAddress">Alamat</label>
                        <textarea class="form-control" id="patientAddress" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Pasien
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Scripts -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#patientTable').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [7] } // Disable ordering on action column
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Form submission
    $('#addPatientForm').on('submit', function(e) {
        e.preventDefault();

        if (validateForm('#addPatientForm')) {
            showLoading();

            // Simulate API call
            setTimeout(() => {
                hideLoading();
                $('#addPatientModal').modal('hide');
                showNotification('success', 'Berhasil', 'Pasien baru berhasil ditambahkan!');

                // Reset form
                this.reset();
            }, 2000);
        } else {
            showNotification('error', 'Validasi Gagal', 'Mohon lengkapi semua field yang wajib diisi.');
        }
    });

    // NIK validation
    $('#patientNIK').on('input', function() {
        const nik = this.value.replace(/\D/g, ''); // Only numbers
        this.value = nik;

        if (nik.length === 16) {
            // Validate NIK format (simplified)
            const isValid = validateNIK(nik);
            if (isValid) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        } else {
            $(this).removeClass('is-valid is-invalid');
        }
    });
});

function validateNIK(nik) {
    // Simplified NIK validation (you should implement proper validation)
    return nik.length === 16 && /^\d{16}$/.test(nik);
}

function exportData(format) {
    showLoading();

    setTimeout(() => {
        hideLoading();
        showNotification('success', 'Export Berhasil', `Data berhasil diekspor ke format ${format.toUpperCase()}`);
    }, 1500);
}

function printTable() {
    window.print();
}
</script>
@endsection
