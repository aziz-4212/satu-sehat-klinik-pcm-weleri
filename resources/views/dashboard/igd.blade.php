@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard IGD</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Dashboard IGD</a></li>
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
                    <h3 class="card-title text-white">Dashboard IGD</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Resource</th>
                                <th>Nama Pelayanan</th>
                                <th>Noreg Terakhir Terkirim</th>
                                <th>Tanggal Terakhir Terkirim</th>
                                <th>Noreg Berjalan</th>
                                <th>Tanggal Terakhir Berjalan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center bg-primary">
                                <td>Pendaftaran Kunjuangan IGD</td>
                                <td>Pembuatan Kunjungan</td>
                                <td>Encounter</td>
                                <td>{{$pembuatan_kunjungan_baru->noreg}}</td>
                                <td>{{$pembuatan_kunjungan_baru->created_at->format('d-M-Y H:i:s')}}_WIB</td>
                                <td>{{ $log_pembuatan_kunjungan_baru->noreg ?? '-' }}</td>
                                <td>{{ $log_pembuatan_kunjungan_baru ? $log_pembuatan_kunjungan_baru->created_at->format('d-M-Y H:i:s') . '_WIB' : '-' }}</td>
                            </tr>

                            <tr class="text-center bg-secondary">
                                <td>Data Triase Dan Gawat Darurat</td>
                                <td>Sarana Transportasi Kedatangan</td>
                                <td>Observation</td>
                                <td>{{$sarana_transportasi_kedatangan->noreg ?? '-' }}</td>
                                <td>{{ $sarana_transportasi_kedatangan ? $sarana_transportasi_kedatangan->created_at->format('d-M-Y H:i:s') : '-' }}_WIB </td>
                                <td>{{ $log_sarana_transportasi_kedatangan->noreg ?? '-' }}</td>
                                <td>{{ $log_sarana_transportasi_kedatangan ? $log_sarana_transportasi_kedatangan->created_at->format('d-M-Y H:i:s') . '_WIB' : '-' }}</td>
                            </tr>
                            <tr class="text-center bg-secondary">
                                <td>Data Triase Dan Gawat Darurat</td>
                                <td>Surat Pengantar Rujukan</td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-secondary">
                                <td>Data Triase Dan Gawat Darurat</td>
                                <td>Kondisi Pasien Tiba</td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-secondary">
                                <td>Data Triase Dan Gawat Darurat</td>
                                <td>Masuk Keruangan Triase</td>
                                <td>Encounter (PUT)</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-success">
                                <td>Anamnesis</td>
                                <td>Keluhan Utama</td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Anamnesis</td>
                                <td>Riwayat Penyakit</td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Anamnesis</td>
                                <td>Riwayat Alergi</td>
                                <td>Allergy Intolerance</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Anamnesis</td>
                                <td>Riwayat Pengobatan</td>
                                <td>Medication Statment</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Anamnesis</td>
                                <td>Masuk Keruangan Tindakan Kebidanan</td>
                                <td>Encounter (PUT)</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>Assesmen Nyeri</td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Skala Nyeri</span><br>
                                    <span class="badge bg-success">Numeric Rating Scale (NRS)</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Skala Nyeri</span><br>
                                    <span class="badge bg-success">Baker Pain Scale (BPS)</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Skala Nyeri</span><br>
                                    <span class="badge bg-success">Neonatal Infant Pain Scale (NIPS)</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    Lokasi Nyeri
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    Penyebab Nyeri
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    Durasi Nyeri
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    Frekuensi Nyeri
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kajian Resiko Jatuh</span><br>
                                    <span class="badge bg-success">Morse Fall Scale</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kajian Resiko Jatuh</span><br>
                                    <span class="badge bg-success">Humpty Dumpty Scale</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kajian Resiko Jatuh</span><br>
                                    <span class="badge bg-success">Edmonson Psychiatric Fall Risk Assesment</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Fisik</span><br>
                                    <span class="badge bg-success">Tingkat Kesadaran</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Fisik</span><br>
                                    <span class="badge bg-success">Vital Sign</span><br>
                                    <span class="badge bg-success">Nadi</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Fisik</span><br>
                                    <span class="badge bg-success">Vital Sign</span><br>
                                    <span class="badge bg-success">Tekanan Darah Sistole</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Fisik</span><br>
                                    <span class="badge bg-success">Vital Sign</span><br>
                                    <span class="badge bg-success">Tekanan Darah Diastole</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Assesmen Awal IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Fisik</span><br>
                                    <span class="badge bg-success">Pemeriksaan Fisik Head to Toe</span><br>
                                    <span class="badge bg-success">Suhu</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-warning">
                                <td>Skrining</td>
                                <td>
                                    Risiko Luka Decubitus
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Skrining</td>
                                <td>
                                    Batuk
                                </td>
                                <td>Quesionare Response</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Skrining</td>
                                <td>
                                    Gizi
                                </td>
                                <td>Quesionare Response</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-info">
                                <td>Pemeriksaan Psikologis</td>
                                <td>
                                    Status Psikologis
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-light">
                                <td>Instruksi Medik dan Keperawatan</td>
                                <td>
                                    Instruksi Medik dan Keperawatan
                                </td>
                                <td>Careplan</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Laboratorium</span><br>
                                    <span class="badge bg-success">Status Puasa</span><br>
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Laboratorium</span><br>
                                    <span class="badge bg-success">Permintaan Pemeriksaan Laboratorium</span><br>
                                </td>
                                <td>Service Request</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Laboratorium</span><br>
                                    <span class="badge bg-success">Data Speciment</span><br>
                                </td>
                                <td>Speciment</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Laboratorium</span><br>
                                    <span class="badge bg-success">Hasil Pemeriksaan Hemoglobin</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Laboratorium</span><br>
                                    <span class="badge bg-success">Hasil Pemeriksaan MCV</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Laboratorium</span><br>
                                    <span class="badge bg-success">Data Laporan Hasil Pemeriksaan Laboratorium</span><br>
                                </td>
                                <td>DiagnosticReport</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Radiologi</span><br>
                                    <span class="badge bg-success">Status Puasa</span><br>
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Radiologi</span><br>
                                    <span class="badge bg-success">Status Kehamilan</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Laboratorium</span><br>
                                    <span class="badge bg-success">Status Allergy Terhadap Bahan Kontras</span><br>
                                </td>
                                <td>AllergyIntolerance</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Radiologi</span><br>
                                    <span class="badge bg-success">For Satu Sehat</span><br>
                                </td>
                                <td>Service Request</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Radiologi</span><br>
                                    <span class="badge bg-success">For MWL didalam DICOM Router</span><br>
                                </td>
                                <td>Serivice Request</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Radiologi</span><br>
                                    <span class="badge bg-success">Hasil Pemeriksaan Hasil USG</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-primary">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    <span class="badge bg-secondary">Radiologi</span><br>
                                    <span class="badge bg-success">Data Laporan Hasil Pemeriksaan USG</span><br>
                                </td>
                                <td>DiagnosticReport</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-secondary">
                                <td>Diagnosis</td>
                                <td>
                                    Diagnosis Awal/Masuk
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-secondary">
                                <td>Diagnosis</td>
                                <td>
                                    Diagnosis Kerja
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-secondary">
                                <td>Diagnosis</td>
                                <td>
                                    Diagnosis Banding
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-success">
                                <td>Tindakan/Prosedure Medis</td>
                                <td>
                                    <span class="badge bg-secondary">Tindakan/Prosedure Medis Diagnostik</span><br>
                                    <span class="badge bg-success">Electrokardiogram</span><br>
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Tindakan/Prosedure Medis</td>
                                <td>
                                    <span class="badge bg-secondary">Tindakan/Prosedure Medis Diagnostik</span><br>
                                    <span class="badge bg-success">Electrokardiogram</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Tindakan/Prosedure Medis</td>
                                <td>
                                    <span class="badge bg-secondary">Tindakan/Prosedure Medis Diagnostik</span><br>
                                    <span class="badge bg-success">Ekokardiografi</span><br>
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Tindakan/Prosedure Medis</td>
                                <td>
                                    <span class="badge bg-secondary">Tindakan/Prosedure Medis Diagnostik</span><br>
                                    <span class="badge bg-success">Ekokardiografi</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Tindakan/Prosedure Medis</td>
                                <td>
                                    <span class="badge bg-secondary">Tindakan/Prosedure Medis Emergensi</span><br>
                                    <span class="badge bg-success">Emergensi - Cesar</span><br>
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-danger">
                                <td>Peresepan Obat</td>
                                <td>
                                    Medication
                                </td>
                                <td>Medication</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Peresepan Obat</td>
                                <td>
                                    Medication Request
                                </td>
                                <td>Medication Request</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-warning">
                                <td>Pengkajian Obat</td>
                                <td>
                                    Pengkajian Resep
                                </td>
                                <td>Quesionare Response</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-info">
                                <td>Pengeluaran Obat</td>
                                <td>
                                    Medication
                                </td>
                                <td>Medication</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-info">
                                <td>Pengeluaran Obat</td>
                                <td>
                                    Medication Dispense
                                </td>
                                <td>Medication Dispense</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-light">
                                <td>Perencanaan Pemulangan Pasien</td>
                                <td>
                                    Kriteria Pasien yang Dilakukan Rencana Pemulangan
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-light">
                                <td>Perencanaan Pemulangan Pasien</td>
                                <td>
                                    Perencanaan Pemulangan Pasien
                                </td>
                                <td>CarePlan</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-dark">
                                <td>Rencana Tindak Lanjut dan Instruksi untuk Tindak Lanjut</td>
                                <td>
                                    Rujukan Keluar Faskes
                                </td>
                                <td>ServiceRequest</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-dark">
                                <td>Rencana Tindak Lanjut dan Instruksi untuk Tindak Lanjut</td>
                                <td>
                                    Rawat Inap Internal
                                </td>
                                <td>ServiceRequest</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-dark">
                                <td>Rencana Tindak Lanjut dan Instruksi untuk Tindak Lanjut</td>
                                <td>
                                    Kontrol 1 Minggu
                                </td>
                                <td>ServiceRequest</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-primary">
                                <td>Kondisi saat Meninggalkan RS</td>
                                <td>
                                    Condition
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-secondary">
                                <td>Cara keluar Dari Rumah Sakit</td>
                                <td>
                                    Cara keluar Dari Rumah Sakit
                                </td>
                                <td>Encounter (PUT)</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-success">
                                <td>Variasi kasus IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kasus Pergantian DPJP</span><br>
                                    <span class="badge bg-success">Masuk Ruangan Tindakan Kebidanan</span><br>
                                </td>
                                <td>Encounter</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Variasi kasus IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kasus Pergantian DPJP</span><br>
                                    <span class="badge bg-success">Masuk Ruangan Tindakan Kebidanan</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Variasi kasus IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kasus Pergantian DPJP</span><br>
                                    <span class="badge bg-success">Diagnosis Kerja</span><br>
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Variasi kasus IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kasus Pergantian DPJP</span><br>
                                    <span class="badge bg-success">Peralihan DPJP</span><br>
                                </td>
                                <td>Encounter (PUT)</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Variasi kasus IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Kasus Pergantian DPJP</span><br>
                                    <span class="badge bg-success">Tindakan</span><br>
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Variasi kasus IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Observation Dalam IGD</span><br>
                                    <span class="badge bg-success">Masuk Ruangan Observasi Dalam IGD</span><br>
                                </td>
                                <td>Encounter (PUT)</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Variasi kasus IGD</td>
                                <td>
                                    <span class="badge bg-secondary">Observation Dalam IGD</span><br>
                                    <span class="badge bg-success">Pulang</span><br>
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
