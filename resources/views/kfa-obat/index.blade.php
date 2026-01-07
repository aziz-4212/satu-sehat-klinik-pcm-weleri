@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Master KFA Obat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Master KFA Obat</a></li>
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
                    <h3 class="card-title text-white">Master KFA Obat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <form action="{{ route('master-kfa-obat.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="id_or_nama">Masukkan ID Satu Sehat atau Nama Obat</label>
                                    <div class="input-group">
                                        <input type="text" name="id_or_nama" class="form-control" placeholder="Masukkan Id atau Nama Obat" value="{{ request('id_or_nama') }}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn bg-teal">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <a href="{{route('master-kfa-obat.create')}}" class="btn bg-teal">Create Master KFA Obat</a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Mabar</th>
                                <th>Nama Mabar</th>
                                <th>Kode KFA</th>
                                <th>Nama Obat KFA</th>
                                <th>Id Satu Sehat</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($master_kfa_obat as $item)
                                <tr>
                                    <td>{{ $item->kode_barang_mabar }}</td>
                                    <td>{{ $item->kode_kfa }}</td>
                                    <td>{{ $item->keterangan_kfa }}</td>
                                    <td>{{ $item->kode_satu_sehat }}</td>
                                    {{-- <td>
                                        <a href="{{ route('master-satuan-obat.edit', $item->id) }}" class="btn bg-teal">Edit</a>
                                        <form action="{{ route('master-satuan-obat.destroy', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn bg-teal">Delete</button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $master_kfa_obat->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>
@endsection
