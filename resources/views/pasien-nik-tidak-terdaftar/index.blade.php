@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pasien Tidak Terdaftar Di Satu Sehat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Pasien Tidak Terdaftar Di Satu Sehat</a></li>
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
                    <h3 class="card-title text-white">Pasien Tidak Terdaftar Di Satu Sehat</h3>
                </div>
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-6">
                            <form action="{{ route('patient.index') }}" method="GET">
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
                            <form action="{{ route('patient.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="nik">Masukkan NIK</label>
                                    <div class="input-group">
                                        <input type="text" name="nik" class="form-control" placeholder="Cari NIK" value="{{ request('nik') }}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn bg-teal">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('patient.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="name">Masukkan Nama</label>
                                    <input type="text" name="name" class="form-control" placeholder="Cari Subject" value="{{ request('name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Masukkan Jenis Kelamin</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male" @if (request('gender') == 'male') selected @endif>Laki-laki</option>
                                        <option value="female" @if (request('gender') == 'female') selected @endif>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Masukkan Tanggal Lahir</label>
                                    <input type="date" name="birthdate" class="form-control" value="{{ request('birthdate') }}">
                                </div>
                                <button type="submit" class="btn bg-teal">Cari</button>
                            </form>
                        </div>
                    </div> --}}
                    <div style="text-align: right;">
                        <a href="{{route('patient.pasien_nik_tidak_terdaftar.download_excel')}}" class="btn bg-teal">Download Data</a>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>No Pasien</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    {{-- <td>{{ $item->id }}</td> --}}
                                    <td>{{ $item->regpas->Pasien->NOKTP }}</td>
                                    <td>{{ $item->regpas->Pasien->NAMAPASIEN }}</td>
                                    <td>{{ $item->regpas->Pasien->NOPASIEN }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $data->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>
@endsection
