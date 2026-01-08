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
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.menu')}}">Rawat Jalan</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rawat-jalan.mencari-data-pasien-dan-nakes.menu')}}">Data Pasien dan Nakes</a></li>
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
                    <h3 class="card-title text-white">Practitioner</h3>
                    <div class="ml-auto">
                        {{-- <a href="{{ route('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.sinkornisasi-data-pegawai') }}" class="btn btn-primary">
                            <span class="text-white"><i class="fas fa-sync-alt"></i> Sinkronisasi Data Personel Pegawai</span>
                        </a> --}}
                        <button type="button" class="btn btn-success" id="btnCreatePractitioner">
                            <span class="text-white"><i class="fas fa-plus"></i> Tambah Practitioner</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Nomer Pegawai</th>
                                <th>Nama</th>
                                <th>NIK KTP</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>NIK KTP</th>
                                <th>Kode Dokter</th>
                                <th>Satu Sehat ID</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawan as $item)
                                <tr>
                                    <td>{{ $item->NIK_pegawai }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->NIK_KTP }}</td>
                                    <td>{{ $item->jenis_kelamin }}</td>
                                    <td>{{ $item->tanggal_lahir }}</td>
                                    <td>{{ $item->kode_dokter ?? 'Bukan Dokter' }}</td>
                                    <td>{{ $item->satu_sehat_id ?? '-' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-edit" data-id="{{ $item->id }}" data-nikpegawai="{{ $item->NIK_pegawai }}" data-nikktp="{{ $item->NIK_KTP }}" data-nama="{{ $item->nama }}" data-kodedokter="{{ $item->kode_dokter }}"><span style="color: white !important;">Edit</span></button>
                                        <a href="{{ route('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.ambil-id-satu-sehat', $item->id) }}" class="btn btn-sm btn-info"><span style="color: white !important;">Ambil ID Satu Sehat</span></a>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"><span style="color: white !important;">Hapus</span></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $karyawan->links('layouts.partials.pagination') }}
            </div>
        </div>
    </section>

    <!-- Create Modal -->
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Practitioner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="practitionerCreateForm">
                        @csrf
                        <div class="form-group">
                            <label>NIK Pegawai</label>
                            <input type="text" name="NIK_pegawai" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>NIK KTP</label>
                            <input type="text" name="NIK_KTP" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kode Dokter</label>
                            <input type="text" name="kode_dokter" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="practitionerCreateSubmit">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Practitioner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="practitionerEditForm">
                        @csrf
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>NIK Pegawai</label>
                            <input type="text" name="NIK_pegawai" id="edit_NIK_pegawai" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>NIK KTP</label>
                            <input type="text" name="NIK_KTP" id="edit_NIK_KTP" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kode Dokter</label>
                            <input type="text" name="kode_dokter" id="edit_kode_dokter" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="practitionerEditSubmit">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong id="deleteName"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="practitionerDeleteConfirm">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function(){
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            // open create modal
            $('#btnCreatePractitioner').on('click', function(){
                $('#modalCreate').modal('show');
            });

            // submit create
            $('#practitionerCreateSubmit').on('click', function(){
                var data = $('#practitionerCreateForm').serialize();
                $.post("{{ route('rawat-jalan.mencari-data-pasien-dan-nakes.practitioner.store') }}", data)
                    .done(function(res){
                        toastr.success(res.message || 'Berhasil disimpan');
                        setTimeout(function(){ location.reload(); }, 800);
                    }).fail(function(xhr){
                        var msg = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        toastr.error(msg);
                    });
            });

            // open edit modal
            $('.btn-edit').on('click', function(){
                var id = $(this).data('id');
                $('#edit_id').val(id);
                $('#edit_NIK_pegawai').val($(this).data('nikpegawai'));
                $('#edit_NIK_KTP').val($(this).data('nikktp'));
                $('#edit_nama').val($(this).data('nama'));
                $('#edit_kode_dokter').val($(this).data('kodedokter'));
                $('#modalEdit').modal('show');
            });

            // submit edit
            $('#practitionerEditSubmit').on('click', function(){
                var id = $('#edit_id').val();
                var data = $('#practitionerEditForm').serialize();
                $.post("/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner/"+id+"/update", data)
                    .done(function(res){
                        toastr.success(res.message || 'Berhasil diperbarui');
                        setTimeout(function(){ location.reload(); }, 800);
                    }).fail(function(xhr){
                        var msg = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        toastr.error(msg);
                    });
            });

            // delete flow
            var deleteId = null;
            $('.btn-delete').on('click', function(){
                deleteId = $(this).data('id');
                $('#deleteName').text($(this).data('nama'));
                $('#modalDelete').modal('show');
            });

            $('#practitionerDeleteConfirm').on('click', function(){
                if (!deleteId) return;
                $.post('/rawat-jalan/menu/mencari-data-pasien-dan-nakes/menu/practitioner/'+deleteId+'/delete', {_method: 'DELETE'})
                    .done(function(res){
                        toastr.success(res.message || 'Berhasil dihapus');
                        setTimeout(function(){ location.reload(); }, 700);
                    }).fail(function(xhr){
                        var msg = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        toastr.error(msg);
                    });
            });
        });
    </script>
    @endpush
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Default Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
