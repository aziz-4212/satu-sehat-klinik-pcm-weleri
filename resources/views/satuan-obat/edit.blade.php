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
                        <li class="breadcrumb-item"><a href="">Edit Master Bentuk Obat</a></li>
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
                    <h3 class="card-title">Edit Master Bentuk Obat</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-satuan-obat.update', [$master_satuan_obat->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="kode_satuan_jual_mabar">Kode Satuan Jual Mabar</label>
                            <input type="text" name="kode_satuan_jual_mabar" id="kode_satuan_jual_mabar" class="form-control" placeholder="Kode Satuan Jual Mabar" value="{{$master_satuan_obat->kode_satuan_jual_mabar}}" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="nama_satuan_jual_mabar">Nama Satuan Jual Mabar</label>
                            <input type="text" name="nama_satuan_jual_mabar" id="nama_satuan_jual_mabar" class="form-control" placeholder="Nama Satuan Jual Mabar" value="{{$master_satuan_obat->nama_satuan_jual_mabar}}" required>
                        </div>
                        <div class="form-group">
                            <label for="kode_satu_sehat">Kode Satu Sehat</label>
                            <input type="text" name="kode_satu_sehat" id="kode_satu_sehat" class="form-control" placeholder="Kode Satu Sehat" value="{{$master_satuan_obat->kode_satu_sehat}}" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_satu_sehat">Nama Satu Sehat</label>
                            <input type="text" name="nama_satu_sehat" id="nama_satu_sehat" class="form-control" placeholder="Nama Satu Sehat" value="{{$master_satuan_obat->nama_satu_sehat}}" required>
                        </div>
                        <a href="{{route('master-satuan-obat.index')}}" style="width: 120px" class="btn bg-teal mb-1"> <i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn bg-teal mb-1">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
