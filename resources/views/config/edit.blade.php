@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Configurasi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a class="warna-teal" href="">Dashboard</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-teal">
                    <h3 class="card-title">Configurasi</h3>
                </div>
                <form method="POST" action="{{ route('config.update', [$config->id]) }}" >
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="text" class="form-control" id="key" name="key" value="{{$config->keys}}" required>
                        </div>
                        <div class="form-group">
                            <label for="value">Value</label>
                            <input type="value" class="form-control" id="value" name="value" value="{{$config->value}}" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a type="button" href="{{route('config.index')}}" class="btn btn-default float-left">Kembali</a>
                        <button type="submit" class="btn bg-teal float-right">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection