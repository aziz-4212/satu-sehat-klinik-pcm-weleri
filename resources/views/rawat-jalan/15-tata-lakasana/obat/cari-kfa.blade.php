@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cari KFA (Medication)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tata-laksana.menu')}}">Tata Laksana</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tata-laksana.obat.medication.index')}}">Obat</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tata-laksana.obat.medication.cari-kfa')}}">Cari KFA (Medication)</a></li>
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
            
            <!-- Form Search KFA -->
            <div class="card mb-3">
                <div class="card-header bg-teal text-white">
                    <h3 class="card-title">Cari KFA (Medication)</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('rawat-jalan.tata-laksana.obat.medication.cari-kfa') }}">
                        <div class="form-row align-items-center">
                            <div class="col-md-6 mb-2">
                                <input type="text" name="search" class="form-control" placeholder="Nama obat/KFA code" value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary" style="color: white !important;">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if(!empty($searchResult) && isset($searchResult->items->data))
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">Hasil Pencarian KFA</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="thead-light">
                                    <tr class="text-center align-middle">
                                        <th style="min-width: 120px;">Nama</th>
                                        <th style="min-width: 90px;">KFA Code</th>
                                        <th style="min-width: 60px;">Active</th>
                                        <th style="min-width: 70px;">State</th>
                                        <th style="min-width: 120px;">Bentuk Sediaan</th>
                                        <th style="min-width: 90px;">Produksi</th>
                                        <th style="min-width: 100px;">NIE</th>
                                        <th style="min-width: 120px;">Nama Dagang</th>
                                        <th style="min-width: 120px;">Manufacturer</th>
                                        <th style="min-width: 120px;">Registrar</th>
                                        <th style="min-width: 70px;">Generik</th>
                                        <th style="min-width: 80px;">RxTerm</th>
                                        <th style="min-width: 70px;">UOM</th>
                                        <th style="min-width: 120px;">Product Template</th>
                                        <th style="min-width: 120px;">Zat Aktif</th>
                                        <th style="min-width: 120px;">Kekuatan Zat Aktif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($searchResult->items->data as $item)
                                        <tr>
                                            <td>{{ $item->name ?? '' }}</td>
                                            <td>{{ $item->kfa_code ?? '' }}</td>
                                            <td class="text-center">{{ $item->active ? 'Ya' : 'Tidak' }}</td>
                                            <td>{{ $item->state ?? '' }}</td>
                                            <td>{{ $item->dosage_form->name ?? '' }}</td>
                                            <td>{{ $item->produksi_buatan ?? '' }}</td>
                                            <td>{{ $item->nie ?? '' }}</td>
                                            <td>{{ $item->nama_dagang ?? '' }}</td>
                                            <td>{{ $item->manufacturer ?? '' }}</td>
                                            <td>{{ $item->registrar ?? '' }}</td>
                                            <td class="text-center">{{ $item->generik ? 'Ya' : 'Tidak' }}</td>
                                            <td>{{ $item->rxterm ?? '' }}</td>
                                            <td>{{ $item->uom->name ?? '' }}</td>
                                            <td>{{ $item->product_template->display_name ?? '' }}</td>
                                            <td>
                                                @if(isset($item->active_ingredients[0]->zat_aktif))
                                                    {{ $item->active_ingredients[0]->zat_aktif }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item->active_ingredients[0]->kekuatan_zat_aktif))
                                                    {{ $item->active_ingredients[0]->kekuatan_zat_aktif }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(isset($searchResult->total) && $searchResult->total > $size)
                            <nav aria-label="KFA Pagination">
                                <ul class="pagination justify-content-center">
                                    @php
                                        $totalPages = ceil($searchResult->total / $size);
                                        $maxPagesToShow = 10;
                                        $startPage = max(1, $page - floor($maxPagesToShow / 2));
                                        $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);
                                        if ($endPage - $startPage + 1 < $maxPagesToShow) {
                                            $startPage = max(1, $endPage - $maxPagesToShow + 1);
                                        }
                                    @endphp
                                    <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                                        <a class="page-link" href="?search={{ $search }}&size={{ $size }}&page={{ $page - 1 }}">&laquo; Prev</a>
                                    </li>
                                    @if($startPage > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="?search={{ $search }}&size={{ $size }}&page=1">1</a>
                                        </li>
                                        @if($startPage > 2)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif
                                    @endif
                                    @for($i = $startPage; $i <= $endPage; $i++)
                                        <li class="page-item {{ $page == $i ? 'active' : '' }}">
                                            <a {{ $page == $i ? 'style=color:white!important;' : '' }} class="page-link" href="?search={{ $search }}&size={{ $size }}&page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    @if($endPage < $totalPages)
                                        @if($endPage < $totalPages - 1)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="?search={{ $search }}&size={{ $size }}&page={{ $totalPages }}">{{ $totalPages }}</a>
                                        </li>
                                    @endif
                                    <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                                        <a class="page-link" href="?search={{ $search }}&size={{ $size }}&page={{ $page + 1 }}">Next &raquo;</a>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
