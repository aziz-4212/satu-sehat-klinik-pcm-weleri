@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tindakan Konseling</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tindakan.menu')}}">Tindakan</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tindakan.konseling.index')}}">Data Konseling</a></li>
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
                    <h3 class="card-title text-white">Tindakan Konseling</h3>
                    <div class="ml-auto">
                        <a href="{{ url()->current() }}" class="btn btn-success mr-2 text-white"><span style="color: white !important;">Success</span></a>
                        <a href="{{ url()->current() }}?status=error" class="btn btn-danger text-white"><span style="color: white !important;">Error</span></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        @if (request('status') == 'error')
                            <thead>
                                <tr class="text-center">
                                    <th>REKAM ID</th>
                                    <th>Error</th>
                                    <th>Tanggal Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->rekam_id }}</td>
                                        <td>{{ $item->ket_log }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <thead>
                                <tr class="text-center">
                                    <th>REKAM ID</th>
                                    <th>Kode Satu Sehat (Encounter)</th>
                                    <th>Service Request Id</th>
                                    <th>Procedure Id</th>
                                    <th>Tanggal Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->rekam_id }}</td>
                                        <td>{{ $item->encounter }}</td>
                                        <td>{{ $item->service_request_id }}</td>
                                        <td>{{ $item->procedure_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
                {{ $data->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>
@endsection
