@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Master Mapmr Loinc</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Master Mapmr Loinc</a></li>
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
            <a href="{{route('master-mapmr-loinc.index')}}" style="width: 120px" class="btn bg-teal mb-1"> <i class="fas fa-arrow-left"></i> Kembali</a>
            <div class="card">
                <div class="card-header bg-teal">
                    <h3 class="card-title text-white">Master Mapmr Loinc</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('master-mapmr-loinc.store') }}">
                    @csrf
                        <button type="submit" class="btn bg-teal">Simpan</button>
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Pilih PMR</h3>
                                        <div class="card-tools">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" id="search-input" class="form-control float-right" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 300px;">
                                        <table class="table table-head-fixed text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Kode PMR</th>
                                                        <th>Nama PMR</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-table-body">
                                                    {{-- @foreach ($mapmr as $item)
                                                        <tr>
                                                            <td><center><input type="checkbox" class="checkbox kodepmr" id="kodepmr[]" name="kodepmr[]" value="{{$item->KODEPMR}}"></center></td>
                                                            <td>{{ $item->KODEPMR }}</td>
                                                            <td>{{ $item->NAMAPMR }}</td>
                                                        </tr>
                                                    @endforeach --}}
                                                </tbody>
                                        </table>
                                    </div>
                                    <div class="pagination justify-content-center">
                                        <button type="button" id="pagination-prev" class="btn btn-primary">&laquo; Previous</button>
                                        <span id="current-page" class="mx-2"></span>
                                        <button type="button" id="pagination-next" class="btn btn-primary">Next &raquo;</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Pilih Loinc</h3>
                                        <div class="card-tools">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" id="search-input-loinc" class="form-control float-right" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 300px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Kode</th>
                                                    <th>Kategori Pemeriksaan</th>
                                                    <th>Nama Pemeriksaan</th>
                                                    <th>Permintaan Hasil</th>
                                                    <th>Spesimen</th>
                                                    <th>Tipe Hasil Pemeriksaan</th>
                                                    <th>Satuan</th>
                                                    <th>Metode Analisis</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-table-body-loinc">
                                                {{-- @foreach ($master_loinc as $item)
                                                    <tr>
                                                        <td><center><input type="checkbox" class="checkbox" id="id_master_loinc[]" name="id_master_loinc[]" value="{{$item->id}}"></center></td>
                                                        <td>{{ $item->kategori_kelompok_pemeriksaan }}</td>
                                                        <td>{{ $item->nama_pemeriksaan }}</td>
                                                        <td>{{ $item->permintaan_hasil }}</td>
                                                        <td>{{ $item->spesimen }}</td>
                                                        <td>{{ $item->tipe_hasil_pemeriksaan }}</td>
                                                        <td>{{ $item->satuan }}</td>
                                                        <td>{{ $item->metode_analisis }}</td>
                                                    </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pagination justify-content-center">
                                        <button type="button" id="pagination-prev-loinc" class="btn btn-primary">&laquo; Previous</button>
                                        <span id="current-page-loinc" class="mx-2"></span>
                                        <button type="button" id="pagination-next-loinc" class="btn btn-primary">Next &raquo;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.kodepmr').change(function() {
                // Periksa apakah kotak centang sedang dicentang
                if ($(this).is(':checked')) {
                    // Nonaktifkan kotak centang lainnya
                    $('.kodepmr').not(this).prop('disabled', true);
                } else {
                    // Aktifkan kembali kotak centang lainnya jika tidak dicentang
                    $('.kodepmr').prop('disabled', false);
                }
            });
        });
    </script>

    {{-- tabel PMR --}}
    <script>
        $(document).ready(function() {
            // Variabel untuk menyimpan halaman saat ini dan jumlah total halaman
            var currentPage = 1;
            var totalPages = 1;

            // Fungsi untuk memuat data dari API
            function loadData(page) {
                $.ajax({
                    url: '/master-mapmr-loinc/data-mapmr?page=' + page, // Ganti dengan URL API yang sesuai
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Kosongkan tabel sebelum menambahkan data baru
                        $('#data-table-body').empty();

                        // Tambahkan data ke dalam tabel
                        response.data.forEach(function(item) {
                            $('#data-table-body').append(`
                                <tr>
                                    <td><center><input type="radio" class="checkbox kodepmr" id="kodepmr[]" name="kodepmr[]" value="${item.KODEPMR}"></center></td>
                                    <td>${item.KODEPMR}</td>
                                    <td>${item.NAMAPMR}</td>
                                </tr>
                            `);
                        });

                        // Perbarui halaman saat ini dan jumlah total halaman
                        currentPage = response.current_page;
                        totalPages = response.last_page;
                        $('#current-page').text('Page ' + currentPage + ' of ' + totalPages);
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan saat memuat data:', error);
                    }
                });
            }

            // Panggil fungsi untuk memuat data saat dokumen siap
            loadData(currentPage);

            // Fungsi untuk menangani perubahan halaman
            $('#pagination-next').click(function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadData(currentPage);
                }
                updatePaginationInfo();
            });

            $('#pagination-prev').click(function() {
                if (currentPage > 1) {
                    currentPage--;
                    loadData(currentPage);
                }
                updatePaginationInfo();
            });

            // Fungsi untuk memperbarui informasi paginasi
            function updatePaginationInfo() {
                $('#current-page').text('Page ' + currentPage + ' of ' + totalPages);
            }

            // Fungsi untuk menangani pencarian
            $('#search-input').on('keyup', function() {

                var keyword = $('#search-input').val();

                $.ajax({
                    url: '/master-mapmr-loinc/search-mapmr?page=' + currentPage + '&keyword=' + keyword,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Kosongkan tabel sebelum menambahkan data baru
                        $('#data-table-body').empty();

                        // Tambahkan data hasil pencarian ke dalam tabel
                        response.data.forEach(function(item) {
                            $('#data-table-body').append(`
                                <tr>
                                    <td><center><input type="checkbox" class="checkbox kodepmr" id="kodepmr[]" name="kodepmr[]" value="${item.KODEPMR}"></center></td>
                                    <td>${item.KODEPMR}</td>
                                    <td>${item.NAMAPMR}</td>
                                </tr>
                            `);
                        });
                        currentPage = response.current_page;
                        totalPages = response.last_page;
                        $('#current-page').text('Page ' + currentPage + ' of ' + totalPages);
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan saat melakukan pencarian:', error);
                    }
                });
            });
        });
    </script>

    {{-- tabel LOINC --}}
    <script>
        $(document).ready(function() {
            // Variabel untuk menyimpan halaman saat ini dan jumlah total halaman
            var currentPage = 1;
            var totalPages = 1;

            // Fungsi untuk memuat data dari API
            function loadData(page) {

                $.ajax({
                    url: '/master-mapmr-loinc/data-loinc?page=' + page, // Ganti dengan URL API yang sesuai
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Kosongkan tabel sebelum menambahkan data baru
                        $('#data-table-body-loinc').empty();

                        // Tambahkan data ke dalam tabel
                        response.data.forEach(function(item) {
                            $('#data-table-body-loinc').append(`
                                <tr>
                                    <td><center><input type="checkbox" class="checkbox" id="id_master_loinc[]" name="id_master_loinc[]" value=${item.id}></center></td>
                                    <td>${item.code}</td>
                                    <td>${item.kategori_kelompok_pemeriksaan}</td>
                                    <td>${item.nama_pemeriksaan}</td>
                                    <td>${item.permintaan_hasil}</td>
                                    <td>${item.spesimen}</td>
                                    <td>${item.tipe_hasil_pemeriksaan}</td>
                                    <td>${item.satuan}</td>
                                    <td>${item.metode_analisis}</td>
                                </tr>
                            `);
                        });

                        // Perbarui halaman saat ini dan jumlah total halaman
                        currentPage = response.current_page;
                        totalPages = response.last_page;
                        $('#current-page-loinc').text('Page ' + currentPage + ' of ' + totalPages);
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan saat memuat data:', error);
                    }
                });
            }

            // Panggil fungsi untuk memuat data saat dokumen siap
            loadData(currentPage);

            // Fungsi untuk menangani perubahan halaman
            $('#pagination-next-loinc').click(function() {
                var keyword = $('#search-input-loinc').val();
                if (currentPage < totalPages && keyword == "") {
                    currentPage++;
                    loadData(currentPage);
                }else{
                    currentPage++;
                    searchLoinc(currentPage, keyword);
                }
                updatePaginationInfo();
            });

            $('#pagination-prev-loinc').click(function() {
                var keyword = $('#search-input-loinc').val();
                if (currentPage > 1 && keyword == "") {
                    currentPage--;
                    loadData(currentPage);
                }else{
                    currentPage--;
                    searchLoinc(currentPage, keyword);
                }
                updatePaginationInfo();
            });

            // Fungsi untuk memperbarui informasi paginasi
            function updatePaginationInfo() {
                $('#current-page-loinc').text('Page ' + currentPage + ' of ' + totalPages);
            }

            // Fungsi untuk menangani pencarian
            $('#search-input-loinc').on('keyup', function() {
                var keyword = $('#search-input-loinc').val();
                $.ajax({
                    url: '/master-mapmr-loinc/search-loinc?page=' + currentPage + '&keyword=' + keyword,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Kosongkan tabel sebelum menambahkan data baru
                        $('#data-table-body-loinc').empty();

                        // Tambahkan data hasil pencarian ke dalam tabel
                        response.data.forEach(function(item) {
                            $('#data-table-body-loinc').append(`
                                <tr>
                                    <td><center><input type="checkbox" class="checkbox" id="id_master_loinc[]" name="id_master_loinc[]" value=${item.id}></center></td>
                                    <td>${item.code}</td>
                                    <td>${item.kategori_kelompok_pemeriksaan}</td>
                                    <td>${item.nama_pemeriksaan}</td>
                                    <td>${item.permintaan_hasil}</td>
                                    <td>${item.spesimen}</td>
                                    <td>${item.tipe_hasil_pemeriksaan}</td>
                                    <td>${item.satuan}</td>
                                    <td>${item.metode_analisis}</td>
                                </tr>
                            `);
                        });
                        currentPage = response.current_page;
                        totalPages = response.last_page;
                        $('#current-page-loinc').text('Page ' + currentPage + ' of ' + totalPages);
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan saat melakukan pencarian:', error);
                    }
                });
            });

            // Fungsi untuk menangani pencarian dengan pagination
            function searchLoinc(currentPage) {
                var keyword = $('#search-input-loinc').val();
                $.ajax({
                    url: '/master-mapmr-loinc/search-loinc?page=' + currentPage + '&keyword=' + keyword,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Kosongkan tabel sebelum menambahkan data baru
                        $('#data-table-body-loinc').empty();

                        // Tambahkan data hasil pencarian ke dalam tabel
                        response.data.forEach(function(item) {
                            $('#data-table-body-loinc').append(`
                                <tr>
                                    <td><center><input type="checkbox" class="checkbox" id="id_master_loinc[]" name="id_master_loinc[]" value=${item.id}></center></td>
                                    <td>${item.code}</td>
                                    <td>${item.kategori_kelompok_pemeriksaan}</td>
                                    <td>${item.nama_pemeriksaan}</td>
                                    <td>${item.permintaan_hasil}</td>
                                    <td>${item.spesimen}</td>
                                    <td>${item.tipe_hasil_pemeriksaan}</td>
                                    <td>${item.satuan}</td>
                                    <td>${item.metode_analisis}</td>
                                </tr>
                            `);
                        });
                        currentPage = response.current_page;
                        totalPages = response.last_page;
                        $('#current-page-loinc').text('Page ' + currentPage + ' of ' + totalPages);
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan saat melakukan pencarian:', error);
                    }
                });
            }
        });
    </script>
@endsection
