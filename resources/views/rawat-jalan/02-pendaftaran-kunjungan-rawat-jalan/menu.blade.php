@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Pendaftaran Kunjungan Rawat Jalan</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="">Pendaftaran Kunjungan Rawat Jalan</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="{{route('rawat-jalan.pendaftaran-kunjungan-rawat-jalan.pembuatan-kunjungan-baru')}}" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-calendar-plus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Pembuatan Kunjungan Baru
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="{{route('rawat-jalan.pendaftaran-kunjungan-rawat-jalan.masuk-ke-ruang-pemeriksaan')}}" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-door-open"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Masuk ke Ruang Pemeriksaan
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
