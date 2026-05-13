@extends('layouts.app')

@section('title', 'Buat Barang Keluar')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection { height: calc(3.5rem + 2px); }
    </style>
@endpush

@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Barang Keluar</h3>
                    <p class="text-subtitle text-muted">Input transaksi pengeluaran barang ke gudang.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('barang-keluar.index') }}">Barang Keluar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="page-content">
        <form action="{{ route('barang-keluar.store') }}" method="POST">
            @csrf
            <div class="row">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi Kesalahan!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Data Header -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="no_transaksi">No. Transaksi</label>
                                <input type="text" name="no_transaksi" class="form-control" value="BK-{{ date('YmdHis') }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="tanggal_keluar">Tanggal Keluar</label>
                                <input type="date" name="tanggal_keluar" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Detail Barang -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pilih Barang</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-9">
                                    <select id="search-barang" class="form-control"></select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="btn-tambah-list" class="btn btn-primary w-100">
                                        <i class="bi bi-plus-circle"></i> Tambah
                                    </button>
                                </div>
                            </div>

                            <hr>

                            <table class="table table-striped mt-3" id="table-detail">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th width="120">Jumlah</th>
                                        <th>Satuan</th>
                                        <th width="50">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Baris barang akan muncul di sini -->
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">Simpan Transaksi</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // 1. Inisialisasi Select2 dengan AJAX Search
            $('#search-barang').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari nama atau kode barang...',
                ajax: {
                    url: "{{ route('barang.search') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.text,
                                    id: item.id,
                                    satuan: item.satuan
                                }
                            })
                        };
                    }
                },
                language: {
                    noResults: function() {
                        return `<button type="button" class="btn btn-sm btn-outline-primary w-100">Barang Tidak ditemukan.</button>`;
                    }
                },
                escapeMarkup: function(markup) { return markup; }
            });

            // 2. Tambah Barang dari Search ke Tabel Detail
            $('#btn-tambah-list').click(function() {
                let data = $('#search-barang').select2('data')[0];

                if (!data) return alert('Pilih barang terlebih dahulu!');

                // Cek apakah barang sudah ada di tabel agar tidak duplikat
                let exists = false;
                $('input[name="barang_id[]"]').each(function() {
                    if ($(this).val() == data.id) exists = true;
                });

                if (exists) return alert('Barang sudah ada di daftar!');

                let satuanTampil = data.satuan;

                let row = `
                    <tr>
                        <td>
                            <input type="hidden" name="barang_id[]" value="${data.id}">
                            ${data.text}
                        </td>
                        <td>
                            <input type="number" name="jumlah[]" class="form-control form-control-sm" value="1" min="1" required>
                        </td>
                        <td>${satuanTampil}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;

                $('#table-detail tbody').append(row);
                $('#search-barang').val(null).trigger('change');
            });

            // 3. Hapus baris tabel
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

        });
    </script>
@endpush