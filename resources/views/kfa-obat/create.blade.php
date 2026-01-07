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
                        <li class="breadcrumb-item"><a href="">Create Master KFA Obat</a></li>
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
                    <h3 class="card-title">Create Master KFA Obat</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-kfa-obat.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="obat">Pilih Obat</label>
                            <select name="obat" id="obat" class="form-control select2">
                                <option value="">Pilih Obat</option>
                                @foreach ($mabar as $item)
                                    <option value="{{ $item->KODEBARANG }}">{{ $item->NAMABARANG }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kode_kfa">Kode KFA</label>
                            <input type="text" name="kode_kfa" id="kode_kfa" class="form-control" placeholder="Kode KFA" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_obat_kfa">Nama Obat KFA</label>
                            <input type="text" name="nama_obat_kfa" id="nama_obat_kfa" class="form-control" placeholder="Nama Obat KFA" required>
                        </div>
                        <a href="{{route('master-kfa-obat.index')}}" style="width: 120px" class="btn bg-teal mb-1"> <i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn bg-teal mb-1">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
