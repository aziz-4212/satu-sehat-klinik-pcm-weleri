@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Organisasi dan Lokasi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="">Organisasi dan Lokasi</a></li>
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
                <div class="card-header bg-teal d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-white">Organisasi dan Lokasi</h3>
                    <div class="ml-auto">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateOrganisation">
                            <span class="text-white"><i class="fas fa-plus"></i> Buat</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode Bagian</th>
                                <th>Nama</th>
                                <th>Sub Organisasi</th>
                                <th>Id Organisation</th>
                                <th>Id Location</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($RJ_00_Organisation_Location as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_bagian }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->parent ? $item->parent->nama : '-' }}</td>
                                    <td>{{ $item->id_organisation }}</td>
                                    <td>{{ $item->id_location }}</td>
                                    <td style="width: 180px;">
                                        @if ($item->id_organisation == null)
                                            <a href="{{route('rawat-jalan.membuat-struktur-organisasi-dan-lokasi.ambil-id-organisasi-satu-sehat', ['id' => $item->id])}}" class="btn btn-sm btn-primary"><span style="color: white !important;">Ambil ID Organisasi Satu Sehat</span></a>
                                        @endif
                                        @if ($item->id_location == null)
                                            <a href="{{route('rawat-jalan.membuat-struktur-organisasi-dan-lokasi.ambil-id-lokasi-satu-sehat', ['id' => $item->id])}}" class="btn btn-sm btn-primary"><span style="color: white !important;">Ambil ID Lokasi Satu Sehat</span></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $RJ_00_Organisation_Location->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>
    <!-- Modal Create Organisation -->
    <div class="modal fade" id="modalCreateOrganisation" tabindex="-1" role="dialog" aria-labelledby="modalCreateOrganisationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('rawat-jalan.membuat-struktur-organisasi-dan-lokasi.store') }}">
                    @csrf
                    <div class="modal-header bg-teal">
                        <h5 class="modal-title text-white" id="modalCreateOrganisationLabel">Tambah Organisasi/Lokasi</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="kode_bagian">Kode Bagian</label>
                            <input type="text" class="form-control" name="kode_bagian" required>
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Parent Organisasi</label>
                            <div style="max-height: 250px; overflow-y: auto;">
                                <select class="form-control" name="parent_id" size="8">
                                    <option value="">- Tidak Ada -</option>
                                    @foreach($data_option_RJ_00_Organisation_Location as $org)
                                        <option value="{{ $org->id }}">{{ $org->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label for="id_organisation">Id Organisation</label>
                            <input type="text" class="form-control" name="id_organisation" required>
                        </div>
                        <div class="form-group">
                            <label for="id_location">Id Location</label>
                            <input type="text" class="form-control" name="id_location" required>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
