@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Location</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Location</a></li>
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
                    <h3 class="card-title text-white">Location</h3>
                </div>
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-6">
                            <form action="{{ route('location.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="id">Cari Berdasarkan ID (Nomer IHS)</label>
                                    <div class="input-group">
                                        <input type="text" name="id" class="form-control" placeholder="Masukkan ID" value="{{ request('id') }}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn bg-teal">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> --}}
                    <div style="text-align: right;">
                        <a href="{{route('location.create')}}" class="btn bg-teal">Create Location <i class="fas fa-plus"></i></a>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID Satu Sehat</th>
                                <th>Kode Poli</th>
                                <th>Nama Poli</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($mode_search == "id")
                                @if ($data->total != 0)
                                    @foreach ($data->entry as $item)
                                        <tr>
                                            <td>{{ optional($item->resource)->id ?? '' }}</td>
                                            <td>{{ optional($item->resource)->identifier[0]->value ?? '' }}</td>
                                            <td>{{ optional($item->resource)->name ?? '' }}</td>
                                            <td>{{ optional($item->resource)->description ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection