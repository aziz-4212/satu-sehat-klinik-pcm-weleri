@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Master Mapmr Loinc</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Master Mapmr Loinc</a></li>
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
                    <h3 class="card-title text-white">Master Mapmr Loinc</h3>
                </div>
                <div class="card-body">
                    <div style="text-align: right;">
                        <a href="{{route('master-mapmr-loinc.create')}}" class="btn bg-teal">Create Master Mapmr Loinc <i class="fas fa-plus"></i></a>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama MAPMR</th>
                                <th>Nama Terminology Loinc</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($master_mapmr_loinc as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->identifier->value }}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
