@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Mater Obat KFA (Medication)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tata-laksana.menu')}}">Tata Laksana</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.tata-laksana.obat.medication.index')}}">Obat</a></li>
                        <li class="breadcrumb-item"><a href="">Mater Obat KFA (Medication)</a></li>
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
                    <h3 class="card-title text-white">Mater Obat KFA (Medication)</h3>
                    <div class="ml-auto">
                        {{-- <button type="button" class="btn btn-primary text-teal" data-toggle="modal" data-target="#modalCreateMedication">
                            <span style="color: white !important;"><i class="fa fa-plus"></i> Tambah Data</span>
                        </button> --}}
                        <button id="btnSyncMedication" type="button" class="btn btn-primary text-teal">
                            <span style="color: white !important;" id="syncSpinner" class="spinner-border spinner-border-sm mr-1 d-none" role="status" aria-hidden="true"></span>
                            <span style="color: white !important;"><i class="fa fa-sync"></i> Sinkronisasi Data Obat ERM</span>
                        </button>
                        <a id="btnCariKfa" type="button" class="btn btn-primary text-teal" href="{{ route('rawat-jalan.tata-laksana.obat.medication.cari-kfa') }}" target="_blank" rel="noopener">
                            <span style="color: white !important;"><i class="fa fa-search"></i> Cari KFA</span>
                        </a>
                    </div>
                    <div id="syncMessage" class="mt-2 text-info font-weight-bold d-none">
                        <i class="fa fa-info-circle"></i> Mohon tunggu, proses sedang berjalan...
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const btnSync = document.getElementById('btnSyncMedication');
                            const btnCariKfa = document.getElementById('btnCariKfa');
                            const spinner = document.getElementById('syncSpinner');
                            const syncMessage = document.getElementById('syncMessage');
                            btnSync.addEventListener('click', function () {
                                btnSync.disabled = true;
                                btnCariKfa.classList.add('disabled');
                                spinner.classList.remove('d-none');
                                syncMessage.classList.remove('d-none');
                                window.location.href = "{{ route('rawat-jalan.tata-laksana.obat.medication.sync') }}";
                            });
                        });
                    </script>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama obat, kode KFA, atau kode RS" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Kode Obat RS</th>
                                <th>Nama Obat RS</th>
                                <th>Kode Obat KFA</th>
                                <th>Nama Obat KFA</th>
                                <th>Kode Satu Sehat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->kode_barang_mabar }}</td>
                                        <td>{{ $item->nama_barang_mabar }}</td>
                                        <td>{{ $item->kode_kfa }}</td>
                                        <td>{{ $item->keterangan_kfa }}</td>
                                        <td>{{ $item->kode_satu_sehat }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEditMedication{{ $item->id }}">
                                                <span style="color: white !important;"><i class="fa fa-edit"></i></span>
                                            </button>
                                            @if ($item->kode_kfa != null && $item->keterangan_kfa != null && $item->kode_satu_sehat == null)
                                                <a class="btn btn-sm btn-primary" href="{{ route('rawat-jalan.tata-laksana.obat.medication.ambil-data-satu-sehat', $item->id) }}">
                                                    <span style="color: white !important;">Ambil Data Satu Sehat</span>
                                                </a>
                                            @endif
                                            <!-- Tombol Hapus -->
                                            {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDeleteMedication{{ $item->id }}">
                                                <span style="color: white !important;"><i class="fa fa-trash"></i></span>
                                            </button> --}}
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $data->links('layouts.partials.pagination') }}
            </div>
            <!-- Modal Create Medication -->
            <div class="modal fade" id="modalCreateMedication" tabindex="-1" role="dialog" aria-labelledby="modalCreateMedicationLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('rawat-jalan.tata-laksana.obat.medication.store') }}">
                            @csrf
                            <div class="modal-header bg-teal">
                                <h5 class="modal-title text-white" id="modalCreateMedicationLabel">Tambah Data Obat</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_barang_mabar">Kode Barang Mabar</label>
                                    <input type="text" class="form-control" name="kode_barang_mabar" required>
                                </div>
                                <div class="form-group">
                                    <label for="kode_kfa">Kode KFA</label>
                                    <input type="text" class="form-control" name="kode_kfa" required>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan_kfa">Keterangan KFA</label>
                                    <input type="text" class="form-control" name="keterangan_kfa" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Edit & Delete Medication (di luar loop) -->
            @foreach ($data as $item)
                <!-- Modal Edit Medication -->
                <div class="modal fade" id="modalEditMedication{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditMedicationLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('rawat-jalan.tata-laksana.obat.medication.update', $item->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title text-white" id="modalEditMedicationLabel{{ $item->id }}">Edit Data Obat</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="kode_barang_mabar">Kode Barang Mabar</label>
                                        <input type="text" class="form-control" name="kode_barang_mabar" value="{{ $item->kode_barang_mabar }}" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_kfa">Kode KFA</label>
                                        <input type="text" class="form-control" name="kode_kfa" value="{{ $item->kode_kfa }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan_kfa">Keterangan KFA</label>
                                        <input type="text" class="form-control" name="keterangan_kfa" value="{{ $item->keterangan_kfa }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_satu_sehat">Kode Satu Sehat</label>
                                        <input type="text" class="form-control" name="kode_satu_sehat" value="{{ $item->kode_satu_sehat }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal Delete Medication -->
                <div class="modal fade" id="modalDeleteMedication{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDeleteMedicationLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('rawat-jalan.tata-laksana.obat.medication.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title text-white" id="modalDeleteMedicationLabel{{ $item->id }}">Konfirmasi Hapus</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus data <strong>{{ $item->keterangan_kfa }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
