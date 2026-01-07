@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Allergy Intolerance / Riwayat Alergi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Allergy Intolerance / Riwayat Alergi</a></li>
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
                    <h3 class="card-title text-white">Allergy Intolerance / Riwayat Alergi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{ route('allergy-intolerance.index.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="id">Masukkan ID Encounter</label>
                                    <div class="input-group">
                                        <input type="text" name="id" class="form-control" placeholder="Cari Id" value="{{ request('id') }}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn bg-teal">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <a href="{{route('allergy-intolerance.create')}}" class="btn bg-teal">Create Allergy Intolerance</a>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id Allergy Intolerance</th>
                                <th>Nama Pasien</th>
                                <th>Kode Snomed</th>
                                <th>Nama Snomed</th>
                                <th>Keterangan Alergi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data != null)
                                <tr>
                                    <td>{{ optional($data)->id ?? '' }}</td>
                                    <td>{{ optional($data)->patient->display ?? '' }}</td>
                                    <td>{{ optional($data)->code->coding[0]->code ?? '' }}</td>
                                    <td>{{ optional($data)->code->text ?? '' }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
