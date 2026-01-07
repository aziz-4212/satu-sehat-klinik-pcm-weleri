@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Data Pasien dan Nakes</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="">Data Pasien dan Nakes</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="{{route('rawat-jalan.mencari-data-pasien-dan-nakes.pasien.index')}}" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-user-injured"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Pasien
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="{{route('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.index')}}" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-user-nurse"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Practitioner
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
