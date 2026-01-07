@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Tata Laksana</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tata-laksana.menu')}}">Tata Laksana</a></li>
                        <li class="breadcrumb-item"><a href="">Obat</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="{{route('rawat-jalan.tata-laksana.obat.medication.index')}}" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-pills"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Master Obat KFA (Medication)
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="#" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-prescription-bottle-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Peresepan Obat (MedicationRequest)
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="#" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-question-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Pengkajian Resep Obat (QuestionnaireResponse)
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                    <a href="#" class="info-box text-white text-decoration-none w-100 text-center"
                       style="background: linear-gradient(135deg, #1B73E8, #52C997);">
                        <span class="info-box-icon"><i class="fas fa-capsules"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-title" style="font-size: 1rem; font-weight: bold;">
                                Pengeluaran Obat (MedicationDispense)
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
