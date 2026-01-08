@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pasien</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.mencari-data-pasien-dan-nakes.menu')}}">Data Pasien dan Nakes</a></li>
                        <li class="breadcrumb-item"><a href="">Pasien</a></li>
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
                <div class="card-header bg-teal d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-white">Pasien</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Nomer RM</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>NIK KTP</th>
                                <th>Satu Sehat ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pasien as $item)
                                <tr>
                                    <td>{{ $item->no_rm }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->tgl_lahir }}</td>
                                    <td>{{ $item->alamat_lengkap }}</td>
                                    <td>{{ $item->no_ktp }}</td>
                                    <td>{{ $item->RJ_01_Patient->satu_sehat_id ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pasien->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>
@endsection
