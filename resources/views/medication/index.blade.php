@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Master Obat (Medication)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Master Obat(Medication)</a></li>
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
                    <h3 class="card-title text-white">Master Obat (Medication)</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{ route('medication.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="id">Masukkan ID Satu Sehat</label>
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
                        <a href="{{route('medication.create')}}" class="btn bg-teal">Create Master Obat (Medication)</a>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id Satu Sehat</th>
                                <th>Kode KFA</th>
                                <th>Deskripsi KFA</th>
                                <th>kode Bentuk Obat</th>
                                <th>Deskripsi Bentuk Obat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data == null)
                            @elseif ($mode_search == "id")
                                <tr>
                                    <td>{{ optional($data)->id ?? '' }}</td>
                                    <td>{{ optional($data)->code->coding[0]->code ?? '' }}</td>
                                    <td>{{ optional($data)->code->coding[0]->display ?? '' }}</td>
                                    <td>{{ optional($data)->form->coding[0]->code ?? '' }}</td>
                                    <td>{{ optional($data)->form->coding[0]->display ?? '' }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
