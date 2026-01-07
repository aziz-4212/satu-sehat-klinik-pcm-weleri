@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard Rawat Jalan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Dashboard Rawat Jalan</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert bg-teal alert-dismissible">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>{{ session('error') }}</strong>
                </div>
            @endif
            <div class="card">
                <div class="card-header bg-teal">
                    <h3 class="card-title text-white">Dashboard Rawat Jalan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Nama Pelayanan</th>
                                <th>Detail Pelayanan</th>
                                <th>Resource</th>
                                <th>Noreg Terakhir Terkirim</th>
                                <th>Tanggal Terakhir Terkirim</th>
                                <th>Noreg Berjalan</th>
                                <th>Tanggal Terakhir Berjalan</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr class="text-center bg-primary">
                                    <td>Pendaftaran Kunjuangan Rawat Jalan</td>
                                    <td>Pembuatan Kunjungan Baru</td>
                                    <td>Encounter</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-center bg-primary">
                                    <td>Pendaftaran Kunjuangan Rawat Jalan</td>
                                    <td>Masuk Ke Ruang Pemeriksaan</td>
                                    <td>Encounter</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="text-center bg-secondary">
                                    <td>Anamnesis</td>
                                    <td>Keluhan Utama</td>
                                    <td>Condition</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-secondary">
                                    <td>Anamnesis</td>
                                    <td>Riwayat Penyakit</td>
                                    <td>Condition</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-secondary">
                                    <td>Anamnesis</td>
                                    <td>Riwayat Alergi</td>
                                    <td>Condition</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-secondary">
                                    <td>Anamnesis</td>
                                    <td>Riwayat Pengobatan</td>
                                    <td>Medication Statment</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="text-center bg-success">
                                    <td>Hasil Pemeriksaan Fisik</td>
                                    <td>Pemeriksaan Tanda-Tanda Vital</td>
                                    <td>Observation</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-center bg-success">
                                    <td>Hasil Pemeriksaan Fisik</td>
                                    <td>Tingkat Kesadaran</td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-success">
                                    <td>Hasil Pemeriksaan Fisik</td>
                                    <td>Pemeriksaan Fisik Head to Toe</td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-success">
                                    <td>Hasil Pemeriksaan Fisik</td>
                                    <td>Pemeriksaan Antropometri</td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>


                                <tr class="text-center bg-danger">
                                    <td>Pemeriksaan Psikologis</td>
                                    <td>Status Psikologis</td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-warning">
                                    <td>Rencana Rawat Pasien</td>
                                    <td>Rencana rawat Pasien</td>
                                    <td>Careplan</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Instruksi Medik Dan Keperawatan</td>
                                    <td>Instruksi Medik Dan Keperawatan</td>
                                    <td>Careplan</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Nominal (Golongan Darah)</span>
                                        <span class="badge bg-warning">Status Puasa</span>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Nominal (Golongan Darah)</span>
                                        <span class="badge bg-warning">Service Request</span>
                                    </td>
                                    <td>Service Request</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Nominal (Golongan Darah)</span>
                                        <span class="badge bg-warning">Speciment</span>
                                    </td>
                                    <td>Speciment</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Nominal (Golongan Darah)</span>
                                        <span class="badge bg-warning">Observation</span>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Nominal (Golongan Darah)</span>
                                        <span class="badge bg-warning">Diagnostic Report</span>
                                    </td>
                                    <td>Diagnostic Report</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Ordinal (BTA)</span>
                                        <span class="badge bg-warning">Status Puasa</span>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Ordinal (BTA)</span>
                                        <span class="badge bg-warning">Service Request</span>
                                    </td>
                                    <td>Service Request</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Ordinal (BTA)</span>
                                        <span class="badge bg-warning">Specimen</span>
                                    </td>
                                    <td>Specimen</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Ordinal (BTA)</span>
                                        <span class="badge bg-warning">Observation</span>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Ordinal (BTA)</span>
                                        <span class="badge bg-warning">DiagnosticReport</span>
                                    </td>
                                    <td>DiagnosticReport</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Kuantitatif (Kolestrol Total)</span>
                                        <span class="badge bg-warning">Status Puasa</span>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Kuantitatif (Kolestrol Total)</span>
                                        <span class="badge bg-warning">Service Request</span>
                                    </td>
                                    <td>Service Request</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Kuantitatif (Kolestrol Total)</span>
                                        <span class="badge bg-warning">Specimen</span>
                                    </td>
                                    <td>Specimen</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Kuantitatif (Kolestrol Total)</span>
                                        <span class="badge bg-warning">Observation</span>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Kuantitatif (Kolestrol Total)</span>
                                        <span class="badge bg-warning">DiagnosticReport</span>
                                    </td>
                                    <td>DiagnosticReport</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Naratif (Pap Simear)</span>
                                        <span class="badge bg-warning">Status Puasa</span>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Naratif (Pap Simear)</span>
                                        <span class="badge bg-warning">Service Request</span>
                                    </td>
                                    <td>Service Request</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Naratif (Pap Simear)</span>
                                        <span class="badge bg-warning">Specimen</span>
                                    </td>
                                    <td>Specimen</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Naratif (Pap Simear)</span>
                                        <span class="badge bg-warning">Observation</span>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Panel Tunggal</span><br>
                                        <span class="badge bg-danger">Panel Naratif (Pap Simear)</span>
                                        <span class="badge bg-warning">Diagnostic Report</span>
                                    </td>
                                    <td>Diagnostic Report</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Paket Pemeriksaan</span><br>
                                        <span class="badge bg-danger">Status Puasa</span>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Paket Pemeriksaan</span><br>
                                        <span class="badge bg-danger">Service Request</span>
                                    </td>
                                    <td>Service Request</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Paket Pemeriksaan</span><br>
                                        <span class="badge bg-danger">Specimen</span>
                                    </td>
                                    <td>Specimen</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Laboratorium</span><br>
                                        <span class="badge bg-success">Paket Pemeriksaan</span><br>
                                        <span class="badge bg-danger">Elektrolit</span>
                                    </td>
                                    <td>Diagnostic Report</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Radiologi</span><br>
                                        <span class="badge bg-success">Pra Pemeriksaan</span><br>
                                        <span class="badge bg-danger">Status Puasa</span>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Radiologi</span><br>
                                        <span class="badge bg-success">Pra Pemeriksaan</span><br>
                                        <span class="badge bg-danger">Status Kehamilan</span>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Radiologi</span><br>
                                        <span class="badge bg-success">Pra Pemeriksaan</span><br>
                                        <span class="badge bg-danger">Status Alergy Terhadap Bahan Kontras</span>
                                    </td>
                                    <td>Allergy Intolerance</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Radiologi</span><br>
                                        <span class="badge bg-success">Pemeriksaan Pemeriksaan X-ray</span><br>
                                        <span class="badge bg-danger">Service Request</span>
                                    </td>
                                    <td>Service Request</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Radiologi</span><br>
                                        <span class="badge bg-success">hasil Pemeriksaan X-ray</span><br>
                                        <span class="badge bg-danger">CXR</span>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-info">
                                    <td>Pemeriksaan Penunjang</td>
                                    <td>
                                        <span class="badge bg-secondary">Radiologi</span><br>
                                        <span class="badge bg-success">Diagnostic Report</span><br>
                                    </td>
                                    <td>Diagnostic Report</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-light">
                                    <td>Tindakan/Procedure Medis</td>
                                    <td>
                                        <span class="badge bg-secondary">Tindakan/Procedure Medis Diagnostic</span><br>
                                        <span class="badge bg-success">Electrokardiogram</span><br>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-light">
                                    <td>Tindakan/Procedure Medis</td>
                                    <td>
                                        <span class="badge bg-secondary">Tindakan/Procedure Medis Diagnostic</span><br>
                                        <span class="badge bg-success">Electrokardiogram</span><br>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-light">
                                    <td>Tindakan/Procedure Medis</td>
                                    <td>
                                        <span class="badge bg-secondary">Tindakan/Procedure Medis Diagnostic</span><br>
                                        <span class="badge bg-success">Ekokardiografi</span><br>
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-light">
                                    <td>Tindakan/Procedure Medis</td>
                                    <td>
                                        <span class="badge bg-secondary">Tindakan/Procedure Medis Diagnostic</span><br>
                                        <span class="badge bg-success">Ekokardiografi</span><br>
                                    </td>
                                    <td>Observation</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-light">
                                    <td>Tindakan/Procedure Medis</td>
                                    <td>
                                        Tindakan/Procedure Medis Terapetik
                                    </td>
                                    <td>Procedure</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-dark">
                                    <td>Diagnosis</td>
                                    <td>
                                        Diagnosis
                                    </td>
                                    <td>Condition</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="text-center bg-dark">
                                    <td>Diet</td>
                                    <td>
                                        Composition
                                    </td>
                                    <td>Composition</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="text-center bg-primary">
                                    <td>Tata Laksana</td>
                                    <td>
                                        Edukasi
                                    </td>
                                    <td>Procedure</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-center bg-primary">
                                    <td>Tata Laksana</td>
                                    <td>
                                        <span class="badge bg-secondary">Obat</span><br>
                                        <span class="badge bg-success">Peresepan Obat</span><br>
                                    </td>
                                    <td>Medication</td>
                                    <td>-</td>
                                    <td></td>
                                    <td>-</td>
                                    <td></td>
                                </tr>
                                <tr class="text-center bg-primary">
                                    <td>Tata Laksana</td>
                                    <td>
                                        <span class="badge bg-secondary">Obat</span><br>
                                        <span class="badge bg-success">Peresepan Obat</span><br>
                                    </td>
                                    <td>Medication Request</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-center bg-primary">
                                    <td>Tata Laksana</td>
                                    <td>
                                        Pengkajian Resep
                                    </td>
                                    <td>Quesionare Response</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-center bg-primary">
                                    <td>Tata Laksana</td>
                                    <td>
                                        Pengeluaran Obat
                                    </td>
                                    <td>Medication Dispense</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr class="text-center bg-primary">
                                    <td>Tata Laksana</td>
                                    <td>
                                        Pengeluaran Obat
                                    </td>
                                    <td>Medication Dispense</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-secondary">
                                    <td>Prognosis</td>
                                    <td>
                                        Prognosis
                                    </td>
                                    <td>Clinicallmpression</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-success">
                                    <td>Rencana Tidak Lanjut Dan Instruksi Tindak Lanjut</td>
                                    <td>
                                        Rencana Tidak Lanjut Dan Instruksi Tindak Lanjut
                                    </td>
                                    <td>Service Request</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                                <tr class="text-center bg-danger">
                                    <td>Cara Keluar Dari Rumah Sakit</td>
                                    <td>
                                        Cara Keluar Dari Rumah Sakit
                                    </td>
                                    <td>Encounter (PUT)</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
