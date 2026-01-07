@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Organization</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Organization</a></li>
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
            <a href="{{route('organization.index')}}" style="width: 120px" class="btn bg-teal mb-1"> <i class="fas fa-arrow-left"></i> Kembali</a>
            <div class="card">
                <div class="card-header bg-teal">
                    <h3 class="card-title text-white">Organization</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('organization.store') }}">
                        @csrf
                        <button type="submit" class="btn bg-teal">Kirim Data Organization</button>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><center><input type="checkbox" id="checkAll"></center></th>
                                    <th>Koders</th>
                                    <th>Nama Poli</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mapping_organization as $item)
                                    <tr>
                                        <td><center><input type="checkbox" class="checkbox" id="koders[]" name="koders[]" value="{{$item->koders}}"></center></td>
                                        <td>{{ $item->koders }}</td>
                                        <td>{{ $item->namars }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                {{ $mapping_organization->links('layouts.partials.pagination') }}
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
