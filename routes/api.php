<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// ===========modul Rawat Jalan========================
    // ===========01. Mencari Data Pasien dan Nakes================
        Route::get('/pasien/ambil-satu-sehat-id', [App\Http\Controllers\RawatJalanController::class, 'pasien_ambil_satu_sehat_id'])->name('pasien.ambil-satu-sehat-id');
    // ===========01. Mencari Data Pasien dan Nakes================
// ===========modul Rawat Jalan========================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//=======================================Resume Medis Rawat Jalan========================================================
Route::prefix('resume-medis-rawat-jalan')->group(function () {
    // ===========01. Mencari Data Pasien dan Nakes================
        Route::get('pasien/ambil-satu-sehat-id', [App\Http\Controllers\RawatJalanController::class, 'pasien_ambil_satu_sehat_id']);
    // ===========01. Mencari Data Pasien dan Nakes================
    // ===========================02. Pendaftaran Kunjungan Rawat Jalan============================
        // ===========================Pembuatan Kunjungan Baru============================
            // Route::get('/pendaftaran-pendataan-pasien/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'pendaftaran_pendataan_pasien_store'])->name('api.resume-medis-rawat-jalan.pendaftaran-pendataan-pasien.store');
            Route::get('/kunjungan-baru', [App\Http\Controllers\RawatJalanController::class, 'pembuatan_kunjungan_baru_api']);
        // ===========================End Pembuatan Kunjungan Baru========================
        // ===========================Masuk ke Ruang Pemeriksaan========================
            Route::get('/masuk-ke-ruang-pemeriksaan', [App\Http\Controllers\RawatJalanController::class, 'masuk_ke_ruang_pemeriksaan_api']);
        // ===========================End Masuk ke Ruang Pemeriksaan========================
    // ===========================End 02. Pendaftaran Kunjungan Rawat Jalan========================

    // =========================10. Pemeriksaan Penunjang=======================
        // =========================Laboratorium=======================
            Route::get('/laboratory', [App\Http\Controllers\RawatJalanController::class, 'laboratory_api']);
        // =========================Laboratorium=======================
        // =========================Radiologi=======================
            Route::get('/radiologi', [App\Http\Controllers\RawatJalanController::class, 'radiologi_api']);
        // =========================Radiologi=======================
    // =========================10. Pemeriksaan Penunjang=======================

    // =========================12. Diagnosis=======================
        Route::get('/diagnosis-primary', [App\Http\Controllers\RawatJalanController::class, 'diagnosis_primary_api']);
    // =========================End 12. Diagnosis=======================
    // ===========15. Tata Laksana========
        Route::get('/peresepan-obat-medication-request', [App\Http\Controllers\RawatJalanController::class, 'peresepan_obat_medication_request_api']);
    // ===========End 15. Tata Laksana========

    // Route::get('/riwayat-alergi', [App\Http\Controllers\ResumeMedisRawatJalanController::class, 'riwayat_alergi_index'])->name('resume-medis-rawat-jalan.riwayat-alergi.index');
    // Route::post('/riwayat-alergi/store', [App\Http\Controllers\ResumeMedisRawatJalanController::class, 'riwayat_alergi_store'])->name('resume-medis-rawat-jalan.riwayat-alergi.store');

    // Route::get('/hasil-pemeriksaan-fisik', [App\Http\Controllers\ResumeMedisRawatJalanController::class, 'hasil_pemeriksaan_fisik_index'])->name('resume-medis-rawat-jalan.hasil-pemeriksaan-fisik.index');
    Route::get('/hasil-pemeriksaan-fisik/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'hasil_pemeriksaan_fisik_store'])->name('api.resume-medis-rawat-jalan.hasil-pemeriksaan-fisik.store');

    // // +++++++++++++++++++++++++++++++++++Rujukan Laboratoriom++++++++++++++++++++++++++++
    //     Route::get('/laboratorium/permintaan-pemeriksaan-penunjang', [App\Http\Controllers\ResumeMedisRawatJalanController::class, 'permintaan_pemeriksaan_penunjang_laboratorium_index'])->name('permintaan-pemeriksaan-penunjang-laboratorium.index');
    //     Route::post('/laboratorium/permintaan-pemeriksaan-penunjang/store', [App\Http\Controllers\ResumeMedisRawatJalanController::class, 'permintaan_pemeriksaan_penunjang_laboratorium_store'])->name('permintaan-pemeriksaan-penunjang-laboratorium.store');

    // // +++++++++++++++++++++++++++++++++++End Rujukan Laboratoriom++++++++++++++++++++++++
    Route::get('/service-request-laboratorium/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'permintaan_pemeriksaan_penunjang_laboratorium_store']);
    Route::get('/medication-request/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'medication_request']);
    Route::get('/tindakan/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'tindakan']);
    Route::get('/resume-diet/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'resume_diet']);
    Route::get('/medication-statement/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'medication_statement']);
    Route::get('/questionnaire-response/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'questionnaire_response']);
    Route::get('/careplan-rencana-rawat-pasien/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'careplan_rencana_rawat_pasien']);
    Route::get('/procedure-edukasi-nutrisi/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'procedure_edukasi_nutrisi']);

    // =================== 17-rencana-tindak-lanjut-dan-instruksi-untuk-tindak-lanjut ============================
        Route::get('/rawat-inap-internal/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'rj_rawat_inap_internal']);
    // =================== End 17-rencana-tindak-lanjut-dan-instruksi-untuk-tindak-lanjut ========================
});

Route::prefix('pelayanan-igd')->group(function () {
    Route::get('/encounter-masuk-kunjungan-igd/store', [App\Http\Controllers\Api\ApiResumeMedisIgdController::class, 'encounter_masuk_kunjungan_igd_store']);
    Route::get('/observation-sarana-transportasi-kedatangan/store', [App\Http\Controllers\Api\ApiResumeMedisIgdController::class, 'observation_sarana_transportasi_kedatangan']);

    // ===================14. Diagnosis=================
        Route::get('/diagnosis-awal-masuk/store', [App\Http\Controllers\Api\ApiResumeMedisIgdController::class, 'diagnosis_awal_masuk']);
    // ===================End 14. Diagnosis=================
});

Route::prefix('resume-medis-rawat-inap')->group(function () {
    Route::get('/rencana-rawat-pasien/store', [App\Http\Controllers\Api\ApiResumeMedisRawatInapController::class, 'careplan_rencana_rawat_pasien']);
    Route::get('/diagnosis/store', [App\Http\Controllers\Api\ApiResumeMedisRawatInapController::class, 'diagnosis']);
});

Route::post('/tindakan/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'tindakan']);
Route::post('/medication/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'medication']);
Route::post('/medication-request/store', [App\Http\Controllers\Api\ApiResumeMedisRawatJalanController::class, 'medication_request']);

Route::prefix('azura')->group(function () {
    Route::get('/recomendation-diagnosis', [App\Http\Controllers\Api\AzuraController::class, 'recomendation_diagnosis']);
});
