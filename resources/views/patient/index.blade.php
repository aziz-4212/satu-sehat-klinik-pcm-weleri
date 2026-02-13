@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Patient</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Patient</a></li>
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
                    <h3 class="card-title text-white">Patient</h3>
                </div>
                <div class="card-body">
                    <div class="row">
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
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id Satu Sehat</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data == null)
                            @elseif ($mode_search == "id")
                                <tr>
                                    <td>{{ optional($data)->id ?? '' }}</td>
                                    <td>{{ $filteredData[1]->value }}</td>
                                    <td>{{ optional($data)->name[0]->text ?? '' }}</td>
                                    <td>{{ optional($data)->gender ?? '' }}</td>
                                    <td>{{ optional($data)->birthDate ?? '' }}</td>
                                    <td>{{ optional($data)->address[0]->city ?? '' }}</td>
                                </tr>
                            @elseif($mode_search == "nik" || $mode_search == "nama")
                                @if ($data->total != 0)
                                    @foreach ($data->entry as $item)
                                        <tr>
                                            <td>{{ optional($item->resource)->id ?? '' }}</td>
                                            <td>{{ optional($item->resource)->name[0]->text ?? '' }}</td>
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
