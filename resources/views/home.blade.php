@extends('layouts.app')

@section('content')
<!-- Welcome Hero Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #1B73E8, #52C997) !important; border: none !important; color: white !important;">
            <div class="card-body py-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 font-weight-bold mb-3" style="color: white !important;">
                            <i class="fas fa-heartbeat mr-3"></i>
                            Selamat Datang di Satu Sehat
                        </h1>
                        <p class="lead mb-4" style="color: white !important; text-shadow: 0 1px 6px rgba(27,115,232,0.10);">
                            Platform terintegrasi untuk mengelola rekam medis dan melakukan sinkronisasi data dengan sistem Satu Sehat Kementerian Kesehatan RI
                        </p>
                        <div class="d-flex flex-wrap">
                            <span class="badge badge-light mr-2 mb-2 px-3 py-2" style="color: #1B73E8;">
                                <i class="fas fa-shield-alt mr-1"></i> Aman & Terpercaya
                            </span>
                            <span class="badge badge-light mr-2 mb-2 px-3 py-2" style="color: #52C997;">
                                <i class="fas fa-sync mr-1"></i> Real-time Sync
                            </span>
                            <span class="badge badge-light mr-2 mb-2 px-3 py-2" style="color: #1B73E8;">
                                <i class="fas fa-hospital mr-1"></i> Multi Faskes
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-inline-block p-4" style="background: rgba(255,255,255,0.1); border-radius: 20px;">
                            <i class="fas fa-hospital-user" style="font-size: 4rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="info-box">
            <span class="info-box-icon" style="background: linear-gradient(135deg, #1B73E8, #4285f4); color: white;">
                <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Total Pasien</span>
                <span class="info-box-number text-primary">1,234</span>
                <div class="progress">
                    <div class="progress-bar bg-primary" style="width: 75%"></div>
                </div>
                <span class="progress-description">
                    <i class="fas fa-arrow-up text-success mr-1"></i> 12% dari bulan lalu
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="info-box">
            <span class="info-box-icon" style="background: linear-gradient(135deg, #52C997, #48bb78); color: white;">
                <i class="fas fa-sync-alt"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Data Tersinkron</span>
                <span class="info-box-number text-success">987</span>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: 80%"></div>
                </div>
                <span class="progress-description">
                    <i class="fas fa-check text-success mr-1"></i> Sinkronisasi aktif
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="info-box">
            <span class="info-box-icon" style="background: linear-gradient(135deg, #FFC107, #ffca28); color: white;">
                <i class="fas fa-user-md"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Dokter Aktif</span>
                <span class="info-box-number text-warning">56</span>
                <div class="progress">
                    <div class="progress-bar bg-warning" style="width: 90%"></div>
                </div>
                <span class="progress-description">
                    <i class="fas fa-user-check text-success mr-1"></i> 54 Terverifikasi KYC
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="info-box">
            <span class="info-box-icon" style="background: linear-gradient(135deg, #DC3545, #e53e3e); color: white;">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Perlu Validasi</span>
                <span class="info-box-number text-danger">23</span>
                <div class="progress">
                    <div class="progress-bar bg-danger" style="width: 30%"></div>
                </div>
                <span class="progress-description">
                    <i class="fas fa-clock text-warning mr-1"></i> Menunggu review
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2 text-primary"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-block mb-3" onclick="window.location.href='{{ route('dashboard.rawat-jalan') }}'">
                        <i class="fas fa-walking mr-2"></i>
                        Dashboard Rawat Jalan
                    </button>
                    <button class="btn btn-warning btn-block mb-3" onclick="window.location.href='{{ route('dashboard.rawat-inap') }}'">
                        <i class="fas fa-bed mr-2"></i>
                        Dashboard Rawat Inap
                    </button>
                    <button class="btn btn-danger btn-block mb-3" onclick="window.location.href='{{ route('dashboard.igd') }}'">
                        <i class="fas fa-ambulance mr-2"></i>
                        Dashboard IGD
                    </button>
                    <button class="btn btn-success btn-block" onclick="syncData()">
                        <i class="fas fa-sync mr-2"></i>
                        Sinkronkan Data
                    </button>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-server mr-2 text-success"></i>
                    Status Sistem
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Koneksi Satu Sehat</span>
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle mr-1"></i> Aktif
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Database</span>
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle mr-1"></i> Normal
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Sinkronisasi</span>
                    <span class="badge badge-info">
                        <i class="fas fa-sync mr-1"></i> Berjalan
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Backup Terakhir</span>
                    <span class="badge badge-secondary">
                        <i class="fas fa-clock mr-1"></i> 2 jam lalu
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Charts -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2 text-info"></i>
                    Statistik Bulanan
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-responsive">
                            <canvas id="patientChart" height="150"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-responsive">
                            <canvas id="syncChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2 text-secondary"></i>
                    Aktivitas Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <i class="fas fa-user-plus text-success mr-2"></i>
                                    Pasien baru terdaftar: <strong>John Doe</strong>
                                </td>
                                <td class="text-right">
                                    <small class="text-muted">5 menit lalu</small>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-sync text-info mr-2"></i>
                                    Data berhasil disinkronkan ke Satu Sehat
                                </td>
                                <td class="text-right">
                                    <small class="text-muted">15 menit lalu</small>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-file-medical text-primary mr-2"></i>
                                    Resume medis baru dibuat untuk <strong>Jane Smith</strong>
                                </td>
                                <td class="text-right">
                                    <small class="text-muted">30 menit lalu</small>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-user-check text-success mr-2"></i>
                                    KYC dokter <strong>Dr. Ahmad</strong> berhasil diverifikasi
                                </td>
                                <td class="text-right">
                                    <small class="text-muted">1 jam lalu</small>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                    Data memerlukan validasi manual
                                </td>
                                <td class="text-right">
                                    <small class="text-muted">2 jam lalu</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Scripts for Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Patient Statistics Chart
    const patientCtx = document.getElementById('patientChart').getContext('2d');
    new Chart(patientCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Pasien Baru',
                data: [120, 150, 180, 200, 170, 210],
                borderColor: '#1B73E8',
                backgroundColor: 'rgba(27, 115, 232, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Sync Statistics Chart
    const syncCtx = document.getElementById('syncChart').getContext('2d');
    new Chart(syncCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tersinkron', 'Pending', 'Error'],
            datasets: [{
                data: [85, 10, 5],
                backgroundColor: ['#52C997', '#FFC107', '#DC3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
