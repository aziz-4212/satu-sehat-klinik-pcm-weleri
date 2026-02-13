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
                                    <td>{{ $RJ_02_A_Kunjungan_Baru->rekam_id }}</td>
                                    <td>{{ $RJ_02_A_Kunjungan_Baru->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_02_A_Kunjungan_Baru_Log->rekam_id }}</td>
                                    <td>{{ $RJ_02_A_Kunjungan_Baru_Log->created_at->format('d F Y H:i:s') }}</td>
                                </tr>
                                <tr class="text-center bg-primary">
                                    <td>Pendaftaran Kunjuangan Rawat Jalan</td>
                                    <td>Masuk Ke Ruang Pemeriksaan</td>
                                    <td>Encounter</td>
                                    <td>{{ $RJ_02_B_Masuk_Ruang->rekam_id }}</td>
                                    <td>{{ $RJ_02_B_Masuk_Ruang->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_02_B_Masuk_Ruang_Log->rekam_id }}</td>
                                    <td>{{ $RJ_02_B_Masuk_Ruang_Log->created_at->format('d F Y H:i:s') }}</td>
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
                                    <td>{{ $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->rekam_id }}</td>
                                    <td>{{ $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->rekam_id }}</td>
                                    <td>{{ $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->created_at->format('d F Y H:i:s') }}</td>
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

                                <tr class="text-center bg-secondary">
                                    <td>Riwayat Perjalanan Penyakit</td>
                                    <td>Riwayat Perjalanan Penyakit</td>
                                    <td>ClinicalImpression</td>
                                    <td>{{ $RJ_06_Riwayat_Perjalanan_Penyakit->rekam_id}}</td>
                                    <td>{{ $RJ_06_Riwayat_Perjalanan_Penyakit->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_06_Riwayat_Perjalanan_Penyakit_Log->rekam_id}}</td>
                                    <td>{{ $RJ_06_Riwayat_Perjalanan_Penyakit_Log->created_at->format('d F Y H:i:s') }}</td>
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
                                        <span class="badge bg-secondary" style="color: white !important;">Laboratorium</span><br>
                                    </td>
                                    <td>Procedure</td>
                                    <td>{{ $RJ_10_Laboratory->rekam_id}}</td>
                                    <td>{{ $RJ_10_Laboratory->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_10_Laboratory_Log->rekam_id}}</td>
                                    <td>{{ $RJ_10_Laboratory_Log->created_at->format('d F Y H:i:s') }}</td>
                                </tr>

                                <tr class="text-center bg-dark">
                                    <td>Diagnosis</td>
                                    <td>
                                        Diagnosis
                                    </td>
                                    <td>Condition</td>
                                    <td>{{ $RJ_12_Diagnosis_Log->rekam_id}}</td>
                                    <td>{{ $RJ_12_Diagnosis_Log->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_12_Diagnosis->rekam_id}}</td>
                                    <td>{{ $RJ_12_Diagnosis->created_at->format('d F Y H:i:s') }}</td>
                                </tr>

                                <tr class="text-center bg-light">
                                    <td>Tindakan/Procedure Medis</td>
                                    <td>
                                        <span class="badge bg-secondary" style="color: white !important;">Konseling</span><br>
                                    </td>
                                    <td>ServiceRequest & Procedure</td>
                                    <td>{{ $RJ_14_Tindakan_Konseling->rekam_id }}</td>
                                    <td>{{ $RJ_14_Tindakan_Konseling->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_14_Tindakan_Konseling_Log->rekam_id }}</td>
                                    <td>{{ $RJ_14_Tindakan_Konseling_Log->created_at->format('d F Y H:i:s') }}</td>
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
                                        <span class="badge bg-secondary" style="color: white !important;">Obat</span><br>
                                        <span class="badge bg-success" style="color: white !important;">Peresepan Obat</span><br>
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
                                        <span class="badge bg-secondary" style="color: white !important;">Obat</span><br>
                                        <span class="badge bg-success" style="color: white !important;">Peresepan Obat</span><br>
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
                                    <td>{{ $RJ_18_Kondisi_Saat_Meninggalkan_Fasyankes_Log->rekam_id}}</td>
                                    <td>{{ $RJ_18_Kondisi_Saat_Meninggalkan_Fasyankes_Log->created_at->format('d F Y H:i:s') }}</td>
                                    <td>{{ $RJ_18_Kondisi_Saat_Meninggalkan_Fasyankes->rekam_id}}</td>
                                    <td>{{ $RJ_18_Kondisi_Saat_Meninggalkan_Fasyankes->created_at->format('d F Y H:i:s') }}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
