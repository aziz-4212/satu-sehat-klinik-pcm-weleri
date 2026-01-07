<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'post_login'])->name('login-post');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');
    Route::resource('/config', App\Http\Controllers\ConfigsController::class);
    Route::resource('/auth', App\Http\Controllers\AuthController::class);
    Route::get('/dashboard-rawat-jalan', [App\Http\Controllers\AuthController::class, 'dashboard_rawat_jalan'])->name('dashboard.rawat-jalan');
    Route::get('/kyc', [App\Http\Controllers\AuthController::class, 'kyc'])->name('dashboard.kyc');
    // ===========modul Rawat Jalan========================
        Route::get('/rawat-jalan/menu', [App\Http\Controllers\RawatJalanController::class, 'menu'])->name('rawat-jalan.menu');
        // ===========00. Membuat Struktur Organisasi dan Lokasi========================
            Route::get('/rawat-jalan/menu/membuat-struktur-organisasi-dan-lokasi', [App\Http\Controllers\RawatJalanController::class, 'membuat_struktur_organisasi_dan_lokasi'])->name('rawat-jalan.membuat-struktur-organisasi-dan-lokasi.index');
            Route::post('/rawat-jalan/menu/membuat-struktur-organisasi-dan-lokasi/store', [App\Http\Controllers\RawatJalanController::class, 'membuat_struktur_organisasi_dan_lokasi_store'])->name('rawat-jalan.membuat-struktur-organisasi-dan-lokasi.store');
            Route::get('/rawat-jalan/menu/membuat-struktur-organisasi-dan-lokasi/ambil-id-organisasi-satu-sehat/{id}', [App\Http\Controllers\RawatJalanController::class, 'ambil_id_organisasi_satu_sehat'])->name('rawat-jalan.membuat-struktur-organisasi-dan-lokasi.ambil-id-organisasi-satu-sehat');
            Route::get('/rawat-jalan/menu/membuat-struktur-organisasi-dan-lokasi/ambil-id-lokasi-satu-sehat/{id}', [App\Http\Controllers\RawatJalanController::class, 'ambil_id_lokasi_satu_sehat'])->name('rawat-jalan.membuat-struktur-organisasi-dan-lokasi.ambil-id-lokasi-satu-sehat');
        // ===========End 00. Membuat Struktur Organisasi dan Lokasi========================
        // ===========01. Mencari Data Pasien dan Nakes================
            Route::get('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu', [App\Http\Controllers\RawatJalanController::class, 'mencari_data_pasien_dan_nakes_menu'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.menu');
            Route::get('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/pasien', [App\Http\Controllers\RawatJalanController::class, 'pasien_index'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.pasien.index');
            Route::get('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner', [App\Http\Controllers\RawatJalanController::class, 'practitioner_index'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.index');
            Route::get('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner/sinkornisasi-data-pegawai', [App\Http\Controllers\RawatJalanController::class, 'practitioner_sinkronisasi_data_pegawai'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.sinkornisasi-data-pegawai');
            // Practitioner CRUD (AJAX-ready)
            Route::post('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner/store', [App\Http\Controllers\RawatJalanController::class, 'practitioner_store'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.store');
            Route::post('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner/{id}/update', [App\Http\Controllers\RawatJalanController::class, 'practitioner_update'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.update');
            Route::post('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner/{id}/delete', [App\Http\Controllers\RawatJalanController::class, 'practitioner_destroy'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.destroy');
            Route::get('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner/{id}/ambil-id-satu-sehat', [App\Http\Controllers\RawatJalanController::class, 'practitioner_ambil_id_satu_sehat'])->name('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.ambil-id-satu-sehat');
        // ===========End 01. Mencari Data Pasien dan Nakes================
        // ===========02. Pendaftaran Kunjungan Rawat Jalan================
            Route::get('/rawat-jalan/menu/pendaftaran-kunjungan-rawat-jalan/menu', [App\Http\Controllers\RawatJalanController::class, 'pendaftaran_kunjungan_rawat_jalan_menu'])->name('rawat-jalan.pendaftaran-kunjungan-rawat-jalan.menu');
            Route::get('/rawat-jalan/menu/pendaftaran-kunjungan-rawat-jalan/menu/pembuatan-kunjungan-baru', [App\Http\Controllers\RawatJalanController::class, 'pembuatan_kunjungan_baru'])->name('rawat-jalan.pendaftaran-kunjungan-rawat-jalan.pembuatan-kunjungan-baru');
            Route::get('/rawat-jalan/menu/pendaftaran-kunjungan-rawat-jalan/menu/masuk-ke-ruang-pemeriksaan', [App\Http\Controllers\RawatJalanController::class, 'masuk_ke_ruang_pemeriksaan'])->name('rawat-jalan.pendaftaran-kunjungan-rawat-jalan.masuk-ke-ruang-pemeriksaan');
        // ===========End 02. Pendaftaran Kunjungan Rawat Jalan========
        // ===========12. Diagnosis========
            Route::get('/rawat-jalan/menu/diagnosis/menu', [App\Http\Controllers\RawatJalanController::class, 'diagnosis_menu'])->name('rawat-jalan.diagnosis.menu');
            Route::get('/rawat-jalan/menu/diagnosis/menu/diagnosis-index', [App\Http\Controllers\RawatJalanController::class, 'diagnosis_index'])->name('rawat-jalan.diagnosis.index');
        // ===========End 12. Diagnosis========
        // ===========15. Tata Laksana========
            Route::get('/rawat-jalan/menu/tata-laksana/menu', [App\Http\Controllers\RawatJalanController::class, 'tata_laksana_menu'])->name('rawat-jalan.tata-laksana.menu');
            Route::get('/rawat-jalan/menu/tata-laksana/menu/obat/menu', [App\Http\Controllers\RawatJalanController::class, 'tata_laksana_obat_menu'])->name('rawat-jalan.tata-laksana.obat.menu');

            // Medication CRUD
            Route::get('/rawat-jalan/menu/tata-laksana/menu/obat/medication', [App\Http\Controllers\RawatJalanController::class, 'medication_index'])->name('rawat-jalan.tata-laksana.obat.medication.index');
            Route::post('/rawat-jalan/menu/tata-laksana/menu/obat/medication/store', [App\Http\Controllers\RawatJalanController::class, 'medication_store'])->name('rawat-jalan.tata-laksana.obat.medication.store');
            Route::put('/rawat-jalan/menu/tata-laksana/menu/obat/medication/{id}/update', [App\Http\Controllers\RawatJalanController::class, 'medication_update'])->name('rawat-jalan.tata-laksana.obat.medication.update');
            Route::delete('/rawat-jalan/menu/tata-laksana/menu/obat/medication/{id}/destroy', [App\Http\Controllers\RawatJalanController::class, 'medication_destroy'])->name('rawat-jalan.tata-laksana.obat.medication.destroy');
            Route::get('/rawat-jalan/menu/tata-laksana/menu/obat/medication/{id}/ambil-data-satu-sehat', [App\Http\Controllers\RawatJalanController::class, 'medication_ambil_data_satu_sehat'])->name('rawat-jalan.tata-laksana.obat.medication.ambil-data-satu-sehat');

            Route::get('/rawat-jalan/menu/tata-laksana/menu/obat/medication/sync', [App\Http\Controllers\RawatJalanController::class, 'medication_sync'])->name('rawat-jalan.tata-laksana.obat.medication.sync');
            Route::get('/rawat-jalan/menu/tata-laksana/menu/obat/medication/cari-kfa', [App\Http\Controllers\RawatJalanController::class, 'medication_cari_kfa'])->name('rawat-jalan.tata-laksana.obat.medication.cari-kfa');
        // ===========End 15. Tata Laksana========
    // ===========modul Rawat Jalan========================

    Route::get('/master-mapmr-loinc/data-mapmr', [App\Http\Controllers\MasterMapmrLoincController::class, 'getData_mapmr']);
    Route::get('/master-mapmr-loinc/search-mapmr', [App\Http\Controllers\MasterMapmrLoincController::class, 'search_mapmr']);
    Route::get('/master-mapmr-loinc/data-loinc', [App\Http\Controllers\MasterMapmrLoincController::class, 'getData_loinc']);
    Route::get('/master-mapmr-loinc/search-loinc', [App\Http\Controllers\MasterMapmrLoincController::class, 'search_loinc']);
    Route::resource('/master-mapmr-loinc', App\Http\Controllers\MasterMapmrLoincController::class);
    Route::get('/encounter', [App\Http\Controllers\EncounterController::class, 'index'])->name('encounter.index');

    Route::get('/patient', [App\Http\Controllers\PatientController::class, 'index'])->name('patient.index');
    Route::get('/patient/create', [App\Http\Controllers\PatientController::class, 'create'])->name('patient.create');
    Route::post('/patient/create/post', [App\Http\Controllers\PatientController::class, 'store'])->name('patient.store');

    Route::get('/pasien-nik-tidak-terdaftar', [App\Http\Controllers\PatientController::class, 'pasien_nik_tidak_terdaftar'])->name('patient.pasien_nik_tidak_terdaftar.index');
    Route::get('/pasien-nik-tidak-terdaftar/download-excel', [App\Http\Controllers\PatientController::class, 'pasien_nik_tidak_terdaftar_download_excel'])->name('patient.pasien_nik_tidak_terdaftar.download_excel');

    Route::get('/practitioner', [App\Http\Controllers\PractitionerController::class, 'index'])->name('practitioner.index');
    Route::get('/practitioner/create', [App\Http\Controllers\PractitionerController::class, 'create'])->name('practitioner.create');
    Route::post('/practitioner/create/post', [App\Http\Controllers\PractitionerController::class, 'store'])->name('practitioner.store');

    Route::resource('/master-satuan-obat', App\Http\Controllers\MasterSatuanObatController::class);
    Route::resource('/master-kfa-obat', App\Http\Controllers\MasterKfaObatController::class);

    Route::get('/medication', [App\Http\Controllers\MedicationController::class, 'index'])->name('medication.index');
    Route::get('/medication/create', [App\Http\Controllers\MedicationController::class, 'create'])->name('medication.create');
    Route::post('/medication/create/post', [App\Http\Controllers\MedicationController::class, 'store'])->name('medication.store');
});
