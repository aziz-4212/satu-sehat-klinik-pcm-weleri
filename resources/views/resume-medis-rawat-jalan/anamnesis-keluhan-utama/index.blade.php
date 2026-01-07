@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Anamnesis - Keluhan Utama (Condition)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Anamnesis - Keluhan Utama (Condition)</a></li>
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
                    <h3 class="card-title text-white">Anamnesis - Keluhan Utama (Condition)</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            @include('resume-medis-rawat-jalan.list-group')
                        </div>
                        <div class="col-9">
                            <form method="GET" action="{{ route('resume-medis-rawat-jalan.keluhan-utama.index') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label style= "padding-rigth: 30px;">Filter Tanggal</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn bg-teal" style="margin-top: 33px" type="submit">Cari</button>
                                    </div>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('resume-medis-rawat-jalan.keluhan-utama.store') }}">
                                @csrf
                                <button type="submit" class="btn bg-teal">Kirim Data Patient</button>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><center><input type="checkbox" id="checkAll"></center></th>
                                            <th>Id Ecoounter</th>
                                            <th>Noreg</th>
                                            <th>Nama</th>
                                            <th>SEP</th>
                                            <th>SEP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($mapping_kunjungan_poli != null)
                                            @foreach ($mapping_kunjungan_poli as $item)
                                                <tr>
                                                    <td><center><input type="checkbox" class="checkbox" id="noreg[]" name="noreg[]" value="{{$item->noreg}}"></center></td>
                                                    <td>{{ $item->encounter }}</td>
                                                    <td>{{ $item->noreg }}</td>
                                                    <td>{{ $item->registrasi_pasien->Pasien->NAMAPASIEN }}</td>
                                                    <td>{{ optional($item->bpjs_insert_sep)->sep }}</td>
                                                    <td>{{ optional($item->checkin)->nosep }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </form>
                            @if ($mapping_kunjungan_poli != null)
                                {{ $mapping_kunjungan_poli->links('layouts.partials.pagination') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle "Check All" functionality
            $('#checkAll').change(function() {
                $('.checkbox').prop('checked', $(this).prop('checked'));
            });

            // Handle individual checkbox change
            $('.checkbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }
            });
        });
    </script>
@endsection
