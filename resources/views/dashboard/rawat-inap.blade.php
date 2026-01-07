@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard Rawat Inap</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Dashboard Rawat Inap</a></li>
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
                    <h3 class="card-title text-white">Dashboard Rawat Inap</h3>
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
                                <td>Pendaftaran Kunjuangan Rawat Inap</td>
                                <td>Masuk Kunjungan Rawat Inap</td>
                                <td>Encounter</td>
                                <td>{{$pembuatan_kunjungan_baru->noreg}}</td>
                                <td>{{$pembuatan_kunjungan_baru->created_at->format('d-M-Y H:i:s')}}_WIB</td>
                                <td>{{ $log_pembuatan_kunjungan_baru->noreg ?? '-' }}</td>
                                <td>{{ $log_pembuatan_kunjungan_baru ? $log_pembuatan_kunjungan_baru->created_at->format('d-M-Y H:i:s') . '_WIB' : '-' }}</td>
                            </tr>

                            <tr class="text-center bg-secondary">
                                <td>Rencana Rawat Pasien</td>
                                <td>Rencana Rawat Pasien</td>
                                <td>Careplan</td>
                                <td>{{$rencana_rawat_pasien->noreg}}</td>
                                <td>{{$rencana_rawat_pasien->created_at->format('d-M-Y H:i:s')}}_WIB</td>
                                <td>{{ $log_rencana_rawat_pasien->noreg ?? '-' }}</td>
                                <td>{{ $log_rencana_rawat_pasien ? $log_rencana_rawat_pasien->created_at->format('d-M-Y H:i:s') . '_WIB' : '-' }}</td>
                            </tr>

                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
                                <td>
                                    <span class="badge bg-secondary">Anamnesis</span><br>
                                    <span class="badge bg-success">Keluhan Utama</span><br>
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
                                <td>
                                    <span class="badge bg-secondary">Anamnesis</span><br>
                                    <span class="badge bg-success">Riwayat Penyakit</span><br>
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
                                <td>
                                    <span class="badge bg-secondary">Anamnesis</span><br>
                                    <span class="badge bg-success">Riwayat Alergi</span><br>
                                </td>
                                <td>AllergyIntolerance</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
                                <td>
                                    <span class="badge bg-secondary">Anamnesis</span><br>
                                    <span class="badge bg-success">Riwayat Pengobatan</span><br>
                                </td>
                                <td>Medication Statment</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
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
                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Fisik</span><br>
                                    <span class="badge bg-success">Vital Sign</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Fisik</span><br>
                                    <span class="badge bg-success">Pemeriksaan Fisik</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-success">
                                <td>Data Formulir Rawat Inap</td>
                                <td>
                                    <span class="badge bg-secondary">Pemeriksaan Psikologis</span><br>
                                    <span class="badge bg-success">Status Psikologis</span><br>
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-danger">
                                <td>Instruksi Medik dan Keperawatan Pasien</td>
                                <td>
                                    Instruksi Medik dan Keperawatan Pasien
                                </td>
                                <td>Careplan</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Laboratorium
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Laboratorium
                                </td>
                                <td>Service Request</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Laboratorium
                                </td>
                                <td>Specimen</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Laboratorium
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Laboratorium
                                </td>
                                <td>Diagnostic Report</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Radiologi
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Radiologi
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Radiologi
                                </td>
                                <td>AllergyIntolerance</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Radiologi
                                </td>
                                <td>Service Request</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-warning">
                                <td>Pemeriksaan Penunjang</td>
                                <td>
                                    Radiologi
                                </td>
                                <td>DiagnosticReport</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-info">
                                <td>Diagnosis</td>
                                <td>
                                    Diagnosis
                                </td>
                                <td>Condition</td>
                                <td>{{$diagnosis->noreg}}</td>
                                <td>{{$diagnosis->created_at->format('d-M-Y H:i:s')}}_WIB</td>
                                <td>{{ $log_diagnosis->noreg ?? '-' }}</td>
                                <td>{{ $log_diagnosis ? $log_diagnosis->created_at->format('d-M-Y H:i:s') . '_WIB' : '-' }}</td>
                            </tr>
                            <tr class="text-center bg-light">
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
                            <tr class="text-center bg-light">
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
                            <tr class="text-center bg-light">
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
                            <tr class="text-center bg-light">
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
                            <tr class="text-center bg-light">
                                <td>Tindakan/Prosedure Medis</td>
                                <td>
                                    Tindakan/Prosedure Medis Terapetik
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-dark">
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
                            <tr class="text-center bg-dark">
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
                            <tr class="text-center bg-primary">
                                <td>Pengkajian Resep</td>
                                <td>
                                    Quesionare Response
                                </td>
                                <td>Quesionare Response</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-secondary">
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
                            <tr class="text-center bg-secondary">
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

                            <tr class="text-center bg-success">
                                <td>Tata Laksana (Edukasi)</td>
                                <td>
                                    Edukasi Proses Penyakit, Diagnosis, Dan Rencana Asuhan
                                </td>
                                <td>Procedure</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-danger">
                                <td>Perencanaan Pemulangan Pasien</td>
                                <td>
                                    Kriteria Pasien Untuk Rencana Pulang
                                </td>
                                <td>Observation</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr class="text-center bg-danger">
                                <td>Perencanaan Pemulangan Pasien</td>
                                <td>
                                    Perencanaan Pemulangan Pasien
                                </td>
                                <td>Careplan</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-warning">
                                <td>Prognosis</td>
                                <td>
                                    Clinicallmpression
                                </td>
                                <td>Clinicallmpression</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-warning">
                                <td>Prognosis</td>
                                <td>
                                    Rencana Tindak Lanjut dan Instruksi Untuk Tindak Lanjut
                                </td>
                                <td>Service Request</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-info">
                                <td>Kondisi Saat Meninggalkan RS</td>
                                <td>
                                    Kondisi Saat Meninggalkan RS
                                </td>
                                <td>Condition</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>

                            <tr class="text-center bg-light">
                                <td>Cara Keluar dari Rumah Sakit</td>
                                <td>
                                    Cara Keluar dari Rumah Sakit
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
