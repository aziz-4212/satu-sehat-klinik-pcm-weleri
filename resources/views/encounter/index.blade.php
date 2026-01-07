@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Encounter (Mode Trial)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Encounter</a></li>
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
                    <h3 class="card-title text-white">Ecounter</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{ route('encounter.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="id">Masukkan ID</label>
                                    <div class="input-group">
                                        <input type="text" name="id" class="form-control" placeholder="Cari Id" value="{{ request('id') }}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn bg-teal">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('encounter.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="subjek">Masukkan Subjek</label>
                                    <div class="input-group">
                                        <input type="text" name="subjek" class="form-control" placeholder="Cari Id" value="{{ request('subjek') }}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn bg-teal">Cari</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($data != null)
                    <pre>
{{ json_encode(json_decode($data), JSON_PRETTY_PRINT) }}
                    </pre>
                    @endif
                    {{-- <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Id</th>
                                <th>Subjek</th>
                                <th>detail</th>
                                <th>diagnostics</th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($id)
                                <tr>
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$responseData->issue[0]->details->text}}</td>
                                    <td>{{$responseData->issue[0]->diagnostics}}</td>
                                    <td></td>
                                    <td>
                                        <a href="" class="btn bg-teal">Update</a>
                                    </td>
                                </tr>
                            @elseif($subject)
                                @foreach ($responseData->entry as $key => $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{$item->resource->issue[0]->details->text}}</td>
                                        <td>{{$item->resource->issue[0]->diagnostics}}</td>
                                        <td></td>
                                    <td>
                                            <a href="{{route('ecounter.edit', [$item->id])}}" class="btn bg-teal">Update</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table> --}}
                </div>
                {{-- @if(isset($responseData->link))
                    @if(isset($responseData->link[2]))
                        <a href="{{ $responseData->link[2]->url }}" class="btn btn-primary">Previous</a>
                    @endif

                    <!-- Menampilkan tombol Next jika ada halaman berikutnya -->
                    @if(isset($responseData->link[1]))
                        <a href="{{ $responseData->link[1]->url }}" class="btn btn-primary">Next</a>
                    @endif
                @endif --}}
            </div>
        </div>
    </section>
@endsection