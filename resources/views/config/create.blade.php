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
                    <h3 class="card-title">Configurasi</h3>
                </div>
                <form method="POST" action="{{ route('config.store') }}" >
                    @csrf
                    <div class="card-body">
                        <h4 class="text-bold">1. Mode Development</h4>
                        <div class="form-group">
                            <label for="client_key_dev">Client Key Development</label>
                            <input type="text" class="form-control" id="client_key_dev" name="client_key_dev" value="{{ $config->client_key_dev ? $config->client_key_dev : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="secret_key_dev">Secret Key Development</label>
                            <input type="text" class="form-control" id="secret_key_dev" name="secret_key_dev" value="{{$config->secret_key_dev}}" required>
                        </div>
                        <div class="form-group">
                            <label for="organization_id_dev">Organization ID Development</label>
                            <input type="text" class="form-control" id="organization_id_dev" name="organization_id_dev" value="{{$config->organization_id_dev}}" required>
                        </div>
                        <div class="form-group">
                            <label for="auth_url_dev">Auth URL Development</label>
                            <input type="text" class="form-control" id="auth_url_dev" name="auth_url_dev" value="{{$config->auth_url_dev}}" required>
                        </div>
                        <div class="form-group">
                            <label for="base_url_dev">Base URL Development</label>
                            <input type="text" class="form-control" id="base_url_dev" name="base_url_dev" value="{{$config->base_url_dev}}" required>
                        </div>
                        <div class="form-group">
                            <label for="consent_url_dev">Consent URL Development</label>
                            <input type="text" class="form-control" id="consent_url_dev" name="consent_url_dev" value="{{$config->consent_url_dev}}" required>
                        </div>
                        <hr>
                        <h4 class="text-bold">2. Mode Staging</h4>
                        <div class="form-group">
                            <label for="client_key_stag">Client Key Staging</label>
                            <input type="text" class="form-control" id="client_key_stag" name="client_key_stag" value="{{$config->client_key_stag}}" required>
                        </div>
                        <div class="form-group">
                            <label for="secret_key_stag">Secret Key Staging</label>
                            <input type="text" class="form-control" id="secret_key_stag" name="secret_key_stag" value="{{$config->secret_key_stag}}" required>
                        </div>
                        <div class="form-group">
                            <label for="organization_id_stag">Organization ID Staging</label>
                            <input type="text" class="form-control" id="organization_id_stag" name="organization_id_stag" value="{{$config->organization_id_stag}}" required>
                        </div>
                        <div class="form-group">
                            <label for="auth_url_stag">Auth URL Staging</label>
                            <input type="text" class="form-control" id="auth_url_stag" name="auth_url_stag" value="{{$config->auth_url_stag}}" required>
                        </div>
                        <div class="form-group">
                            <label for="base_url_stag">Base URL Staging</label>
                            <input type="text" class="form-control" id="base_url_stag" name="base_url_stag" value="{{$config->base_url_stag}}" required>
                        </div>
                        <div class="form-group">
                            <label for="consent_url_stag">Consent URL Staging</label>
                            <input type="text" class="form-control" id="consent_url_stag" name="consent_url_stag" value="{{$config->consent_url_stag}}" required>
                        </div>
                        <hr>
                        <h4 class="text-bold">3. Mode Production</h4>
                        <div class="form-group">
                            <label for="client_key_prod">Client Key Production</label>
                            <input type="text" class="form-control" id="client_key_prod" name="client_key_prod" value="{{$config->client_key_prod}}" required>
                        </div>
                        <div class="form-group">
                            <label for="secret_key_prod">Secret Key Production</label>
                            <input type="text" class="form-control" id="secret_key_prod" name="secret_key_prod" value="{{$config->secret_key_prod}}" required>
                        </div>
                        <div class="form-group">
                            <label for="organization_id_prod">Organization ID Production</label>
                            <input type="text" class="form-control" id="organization_id_prod" name="organization_id_prod" value="{{$config->organization_id_prod}}" required>
                        </div>
                        <div class="form-group">
                            <label for="auth_url_prod">Auth URL Production</label>
                            <input type="text" class="form-control" id="auth_url_prod" name="auth_url_prod" value="{{$config->auth_url_prod}}" required>
                        </div>
                        <div class="form-group">
                            <label for="base_url_prod">Base URL Production</label>
                            <input type="text" class="form-control" id="base_url_prod" name="base_url_prod" value="{{$config->base_url_prod}}" required>
                        </div>
                        <div class="form-group">
                            <label for="consent_url_prod">Consent URL Production</label>
                            <input type="text" class="form-control" id="consent_url_prod" name="consent_url_prod" value="{{$config->consent_url_prod}}" required>
                        </div>
                        <hr>
                        <h4 class="text-bold">4. Mode Active</h4>
                        <div class="form-group">
                            <label for="mode">Aktif Mode</label>
                            <select class="form-control" name="mode" id="mode" required>
                                {{-- <option value="">Pilih Mode</option> --}}
                                <option value="dev" @if ($config->mode == "dev") selected @endif>Development</option>
                                <option value="stag" @if ($config->mode == "stag") selected @endif>Staging</option>
                                <option value="prod" @if ($config->mode == "prod") selected @endif>Production</option>
                            </select>
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