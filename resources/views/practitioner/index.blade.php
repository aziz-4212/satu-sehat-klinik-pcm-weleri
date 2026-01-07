@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Practitioner</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Practitioner</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert bg-teal alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>{{ session('error') }}</strong>
                </div>
            @endif
            <div class="card">
                <div class="card-header bg-teal">
                    <h3 class="card-title">Practitioner</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{ route('practitioner.index') }}" method="GET">
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
                            <form action="{{ route('practitioner.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="nik">Cari Berdasarkan NIK</label>
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
                            <form action="{{ route('practitioner.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" class="form-control" placeholder="Masukkan Nama" value="{{ request('name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male" @if (request('gender') == 'male') selected @endif>Laki-laki</option>
                                        <option value="female" @if (request('gender') == 'female') selected @endif>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Tahun Lahir</label>
                                    <select name="birthdate" class="form-control">
                                        @for ($year = 1950; $year <= $tahun_sekarang; $year++)
                                            <option value="{{ $year }}" {{ request('birthdate') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                    {{-- <input type="date" name="birthdate" class="form-control" value="{{ request('birthdate') }}"> --}}
                                </div>
                                <button type="submit" class="btn bg-teal">Cari</button>
                            </form>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <a href="{{route('practitioner.create')}}" class="btn bg-teal">Create Practitioner</a>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nomor IHS</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                {{-- <th style="width: 20%">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data == null)
                            @elseif ($mode_search == "id")
                                <tr>
                                    @php
                                        $systemToFind = "https://fhir.kemkes.go.id/id/nik";
                                        $item = optional($data)->identifier ?? null;
                                        if ($data != null) {
                                            $filteredData = array_filter($item, function ($item2) use ($systemToFind) {
                                                return isset($item2->system) && $item2->system === $systemToFind;
                                            });
                                        }
                                    @endphp
                                    <td>{{ optional($data)->id ?? '' }}</td>
                                    <td>{{ $filteredData[1]->value }}</td>
                                    <td>{{ optional($data)->name[0]->text ?? '' }}</td>
                                    <td>{{ optional($data)->gender ?? '' }}</td>
                                    <td>{{ optional($data)->birthDate ?? '' }}</td>
                                    <td>{{ optional($data)->address[0]->city ?? '' }}</td>
                                </tr>
                            @elseif($mode_search == "nik" || $mode_search == "nama")
                                @foreach ($data->entry as $item)
                                    <tr>
                                        @php
                                            $systemToFind = "https://fhir.kemkes.go.id/id/nik";
                                            $data = optional($item->resource)->identifier ?? null;
                                            if ($data != null) {
                                                $filteredData = array_filter($data, function ($item2) use ($systemToFind) {
                                                    return isset($item2->system) && $item2->system === $systemToFind;
                                                });
                                            }
                                        @endphp
                                        <td>{{ optional($item->resource)->id ?? '' }}</td>
                                        <td>{{ isset($filteredData[1]) ? optional($filteredData[1])->value : '' }}</td>
                                        <td>{{ optional($item->resource->name[0])->text ?? '' }}</td>
                                        <td>{{ optional($item->resource)->gender ?? '' }}</td>
                                        <td>{{ optional($item->resource)->birthDate ?? '' }}</td>
                                        <td>{{ optional($item->resource)->address[0]->city ?? '' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- @if(isset($data->link))
                    @if(isset($data->link[2]))
                        <a href="{{ $data->link[2]->url }}" class="btn btn-primary">Previous</a>
                    @endif

                    <!-- Menampilkan tombol Next jika ada halaman berikutnya -->
                    @if(isset($data->link[1]))
                        <a href="{{ $data->link[1]->url }}" class="btn btn-primary">Next</a>
                    @endif
                @endif --}}
            </div>
        </div>
    </section>
@endsection
