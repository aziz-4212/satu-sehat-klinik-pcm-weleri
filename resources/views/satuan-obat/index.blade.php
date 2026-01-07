@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Master Bentuk Obat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Master Bentuk Obat</a></li>
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
                    <h3 class="card-title text-white">Master Bentuk Obat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <form action="{{ route('master-satuan-obat.index') }}" method="GET">
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
                        <a href="{{route('master-satuan-obat.create')}}" class="btn bg-teal">Create Master Bentuk Obat</a>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Satuan Jual Mabar</th>
                                <th>Nama Satuan Jual Mabar</th>
                                <th>Kode Satu Sehat</th>
                                <th>Nama Kode Satu Sehat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($master_satuan_obat as $item)
                                <tr>
                                    <td>{{ $item->kode_satuan_jual_mabar }}</td>
                                    <td>{{ $item->nama_satuan_jual_mabar }}</td>
                                    <td>{{ $item->kode_satu_sehat }}</td>
                                    <td>{{ $item->nama_satu_sehat }}</td>
                                    <td>
                                        <a href="{{ route('master-satuan-obat.edit', $item->id) }}" class="btn bg-teal">Edit</a>
                                        <form action="{{ route('master-satuan-obat.destroy', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn bg-teal">Delete</button>
                                        </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $master_satuan_obat->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>
@endsection
