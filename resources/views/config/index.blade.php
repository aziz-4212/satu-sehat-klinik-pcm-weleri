@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Konfigurasi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Konfigurasi</a></li>
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
                    <h3 class="card-title text-white">Konfigurasi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <!-- Konten lain di sini -->
                        </div>
                        <div class="col-auto">
                            <a href="{{route('config.create')}}" class="btn bg-teal">+Tambah</a>
                        </div>
                    </div>
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Key</th>
                                <th>Value</th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($config as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->keys}}</td>
                                <td>{{$item->value}}</td>
                                <td>
                                    <a href="{{route('config.edit', [$item->id])}}" class="btn bg-teal">Update</a>
                                    <button type="button" onClick="remove({{ $item->id }})" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $config->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>
    @include('config.delete')
@endsection