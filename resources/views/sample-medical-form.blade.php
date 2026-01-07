@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-2 text-gray-800">
                    <i class="fas fa-file-medical text-primary mr-2"></i>
                    Form Resume Medis Pasien
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-primary text-decoration-none">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#" class="text-primary text-decoration-none">Rekam Medis</a>
                        </li>
                        <li class="breadcrumb-item active">Form Resume Medis</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="btn btn-outline-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </button>
                <button class="btn btn-success" onclick="saveDraft()">
                    <i class="fas fa-save mr-2"></i>Simpan Draft
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Patient Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle mr-2"></i>
                    Informasi Pasien
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                JD
                            </div>
                            <h5 class="mb-1">John Doe</h5>
                            <p class="text-muted mb-0">Laki-laki, 33 Tahun</p>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>No. Rekam Medis:</strong>
                                <p>MR001234</p>
                            </div>
                            <div class="col-md-4">
                                <strong>NIK:</strong>
                                <p>1234567890123456</p>
                            </div>
                            <div class="col-md-4">
                                <strong>No. BPJS:</strong>
                                <p>0001234567890</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Tanggal Lahir:</strong>
                                <p>15 Januari 1990</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Alamat:</strong>
                                <p>Jl. Merdeka No. 123, Jakarta</p>
                            </div>
                            <div class="col-md-4">
                                <strong>No. Telepon:</strong>
                                <p>+62812-3456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Medical Record Form -->
<form id="medicalRecordForm">
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Anamnesis -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-comments text-info mr-2"></i>
                        Anamnesis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="keluhanUtama">Keluhan Utama *</label>
                        <textarea class="form-control" id="keluhanUtama" rows="3" placeholder="Deskripsikan keluhan utama pasien..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="riwayatPenyakitSekarang">Riwayat Penyakit Sekarang *</label>
                        <textarea class="form-control" id="riwayatPenyakitSekarang" rows="4" placeholder="Deskripsikan riwayat penyakit sekarang..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="riwayatPenyakitDahulu">Riwayat Penyakit Dahulu</label>
                                <textarea class="form-control" id="riwayatPenyakitDahulu" rows="3" placeholder="Riwayat penyakit terdahulu..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="riwayatPenyakitKeluarga">Riwayat Penyakit Keluarga</label>
                                <textarea class="form-control" id="riwayatPenyakitKeluarga" rows="3" placeholder="Riwayat penyakit dalam keluarga..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="riwayatAlergi">Riwayat Alergi</label>
                                <select class="form-control select2" id="riwayatAlergi" multiple>
                                    <option value="obat">Alergi Obat</option>
                                    <option value="makanan">Alergi Makanan</option>
                                    <option value="lingkungan">Alergi Lingkungan</option>
                                    <option value="tidak_ada">Tidak Ada Alergi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="detailAlergi">Detail Alergi</label>
                                <input type="text" class="form-control" id="detailAlergi" placeholder="Sebutkan detail alergi...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemeriksaan Fisik -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-stethoscope text-success mr-2"></i>
                        Pemeriksaan Fisik
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Vital Signs -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-heartbeat mr-2"></i>Tanda Vital
                            </h6>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tekananDarah">Tekanan Darah</label>
                                <input type="text" class="form-control" id="tekananDarah" placeholder="120/80 mmHg">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nadi">Nadi</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="nadi" placeholder="80">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suhu">Suhu Tubuh</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" class="form-control" id="suhu" placeholder="36.5">
                                    <div class="input-group-append">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pernapasan">Pernapasan</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="pernapasan" placeholder="20">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Physical Examination -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="beratBadan">Berat Badan</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" class="form-control" id="beratBadan" placeholder="70">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tinggiBadan">Tinggi Badan</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="tinggiBadan" placeholder="170">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pemeriksaanFisik">Hasil Pemeriksaan Fisik *</label>
                        <textarea class="form-control" id="pemeriksaanFisik" rows="4" placeholder="Deskripsikan hasil pemeriksaan fisik..." required></textarea>
                    </div>
                </div>
            </div>

            <!-- Diagnosis -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-diagnoses text-warning mr-2"></i>
                        Diagnosis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="diagnosisPrimer">Diagnosis Primer (ICD-10) *</label>
                        <select class="form-control select2" id="diagnosisPrimer" required>
                            <option value="">Pilih Diagnosis Primer</option>
                            <option value="J00">J00 - Acute nasopharyngitis (common cold)</option>
                            <option value="K59.1">K59.1 - Diarrhoea, unspecified</option>
                            <option value="I10">I10 - Essential (primary) hypertension</option>
                            <option value="E11.9">E11.9 - Type 2 diabetes mellitus without complications</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="diagnosisSekunder">Diagnosis Sekunder (ICD-10)</label>
                        <select class="form-control select2" id="diagnosisSekunder" multiple>
                            <option value="">Pilih Diagnosis Sekunder</option>
                            <option value="Z87.891">Z87.891 - Personal history of nicotine dependence</option>
                            <option value="E78.5">E78.5 - Hyperlipidaemia, unspecified</option>
                            <option value="M79.3">M79.3 - Panniculitis, unspecified</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="diagnosisBanding">Diagnosis Banding</label>
                        <textarea class="form-control" id="diagnosisBanding" rows="3" placeholder="Sebutkan diagnosis banding jika ada..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Treatment Plan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-pills text-danger mr-2"></i>
                        Rencana Terapi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="terapiFarmakologi">Terapi Farmakologi</label>
                        <div id="medicationList">
                            <div class="medication-item border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Nama Obat</label>
                                        <select class="form-control select2 medication-name">
                                            <option value="">Pilih Obat</option>
                                            <option value="paracetamol">Paracetamol 500mg</option>
                                            <option value="amoxicillin">Amoxicillin 500mg</option>
                                            <option value="omeprazole">Omeprazole 20mg</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Dosis</label>
                                        <input type="text" class="form-control" placeholder="3x1">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Bentuk</label>
                                        <select class="form-control">
                                            <option>Tablet</option>
                                            <option>Kapsul</option>
                                            <option>Sirup</option>
                                            <option>Injeksi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Aturan Pakai</label>
                                        <input type="text" class="form-control" placeholder="Sesudah makan">
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeMedication(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMedication()">
                            <i class="fas fa-plus mr-2"></i>Tambah Obat
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="terapiNonFarmakologi">Terapi Non-Farmakologi</label>
                        <textarea class="form-control" id="terapiNonFarmakologi" rows="3" placeholder="Edukasi, diet, olahraga, dll..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Visit Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="fas fa-calendar-alt text-primary mr-2"></i>
                        Informasi Kunjungan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="tanggalKunjungan">Tanggal Kunjungan *</label>
                        <input type="datetime-local" class="form-control" id="tanggalKunjungan" required>
                    </div>

                    <div class="form-group">
                        <label for="jenisKunjungan">Jenis Kunjungan *</label>
                        <select class="form-control" id="jenisKunjungan" required>
                            <option value="">Pilih Jenis Kunjungan</option>
                            <option value="konsultasi">Konsultasi</option>
                            <option value="kontrol">Kontrol</option>
                            <option value="emergency">Gawat Darurat</option>
                            <option value="rujukan">Rujukan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dokterPemeriksa">Dokter Pemeriksa *</label>
                        <select class="form-control select2" id="dokterPemeriksa" required>
                            <option value="">Pilih Dokter</option>
                            <option value="dr1">Dr. Ahmad Budiman, Sp.PD</option>
                            <option value="dr2">Dr. Siti Nurhaliza, Sp.A</option>
                            <option value="dr3">Dr. Budi Santoso, Sp.OG</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="poliklinik">Poliklinik</label>
                        <select class="form-control" id="poliklinik">
                            <option value="poli_umum">Poli Umum</option>
                            <option value="poli_dalam">Poli Dalam</option>
                            <option value="poli_anak">Poli Anak</option>
                            <option value="poli_kandungan">Poli Kandungan</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Laboratory Results -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="fas fa-flask text-info mr-2"></i>
                        Hasil Laboratorium
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Upload Hasil Lab</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="labResults" multiple accept=".pdf,.jpg,.jpeg,.png">
                            <label class="custom-file-label" for="labResults">Pilih file...</label>
                        </div>
                        <small class="text-muted">Format: PDF, JPG, PNG. Max 5MB per file.</small>
                    </div>

                    <div class="form-group">
                        <label for="catatanLab">Catatan Hasil Lab</label>
                        <textarea class="form-control" id="catatanLab" rows="3" placeholder="Interpretasi hasil laboratorium..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Follow Up -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="fas fa-calendar-check text-success mr-2"></i>
                        Tindak Lanjut
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="tindakLanjut">Jenis Tindak Lanjut</label>
                        <select class="form-control" id="tindakLanjut">
                            <option value="kontrol">Kontrol Rutin</option>
                            <option value="rujuk">Rujuk ke Spesialis</option>
                            <option value="rawat_inap">Rawat Inap</option>
                            <option value="selesai">Pengobatan Selesai</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggalKontrol">Tanggal Kontrol</label>
                        <input type="date" class="form-control" id="tanggalKontrol">
                    </div>

                    <div class="form-group">
                        <label for="catatanKhusus">Catatan Khusus</label>
                        <textarea class="form-control" id="catatanKhusus" rows="3" placeholder="Instruksi khusus atau peringatan..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save mr-2"></i>Simpan Resume Medis
                        </button>
                        <button type="button" class="btn btn-success btn-block" onclick="submitAndSync()">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Simpan & Kirim ke Satu Sehat
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-block" onclick="previewResume()">
                            <i class="fas fa-eye mr-2"></i>Preview Resume
                        </button>
                        <button type="button" class="btn btn-outline-info btn-block" onclick="printResume()">
                            <i class="fas fa-print mr-2"></i>Cetak Resume
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Custom Scripts -->
<script>
$(document).ready(function() {
    // Set current datetime
    const now = new Date();
    const formattedDateTime = now.toISOString().slice(0, 16);
    $('#tanggalKunjungan').val(formattedDateTime);

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Auto-save functionality
    let autoSaveTimer;
    $('form input, form textarea, form select').on('change', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            saveDraft();
        }, 5000); // Auto-save after 5 seconds of inactivity
    });

    // Form submission
    $('#medicalRecordForm').on('submit', function(e) {
        e.preventDefault();

        if (validateForm('#medicalRecordForm')) {
            showLoading();

            // Simulate API call
            setTimeout(() => {
                hideLoading();
                showNotification('success', 'Berhasil', 'Resume medis berhasil disimpan!');
            }, 2000);
        } else {
            showNotification('error', 'Validasi Gagal', 'Mohon lengkapi semua field yang wajib diisi.');
        }
    });

    // File upload handler
    $('#labResults').on('change', function() {
        const files = this.files;
        let fileNames = [];

        for (let i = 0; i < files.length; i++) {
            fileNames.push(files[i].name);
        }

        $(this).next('.custom-file-label').text(
            files.length > 1 ? `${files.length} files selected` : fileNames[0] || 'Choose files...'
        );
    });
});

function addMedication() {
    const medicationTemplate = `
        <div class="medication-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label>Nama Obat</label>
                    <select class="form-control select2 medication-name">
                        <option value="">Pilih Obat</option>
                        <option value="paracetamol">Paracetamol 500mg</option>
                        <option value="amoxicillin">Amoxicillin 500mg</option>
                        <option value="omeprazole">Omeprazole 20mg</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Dosis</label>
                    <input type="text" class="form-control" placeholder="3x1">
                </div>
                <div class="col-md-2">
                    <label>Bentuk</label>
                    <select class="form-control">
                        <option>Tablet</option>
                        <option>Kapsul</option>
                        <option>Sirup</option>
                        <option>Injeksi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Aturan Pakai</label>
                    <input type="text" class="form-control" placeholder="Sesudah makan">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeMedication(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    $('#medicationList').append(medicationTemplate);

    // Initialize Select2 for new medication select
    $('#medicationList .medication-item:last .select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
}

function removeMedication(button) {
    $(button).closest('.medication-item').remove();
}

function saveDraft() {
    showNotification('info', 'Auto-save', 'Draft telah disimpan otomatis');
}

function submitAndSync() {
    if (validateForm('#medicalRecordForm')) {
        showLoading();

        // Simulate API call to save and sync
        setTimeout(() => {
            hideLoading();
            showNotification('success', 'Berhasil', 'Resume medis berhasil disimpan dan dikirim ke Satu Sehat!');
        }, 3000);
    } else {
        showNotification('error', 'Validasi Gagal', 'Mohon lengkapi semua field yang wajib diisi.');
    }
}

function previewResume() {
    // Implementation for resume preview
    showNotification('info', 'Preview', 'Membuka preview resume medis...');
}

function printResume() {
    // Implementation for printing
    window.print();
}
</script>
@endsection
