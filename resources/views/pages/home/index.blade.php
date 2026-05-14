@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stats-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.75rem;
            display: flex !important; /* Gunakan flex */
            align-items: center !important; /* Center vertikal */
            justify-content: center !important; /* Center horizontal */
            flex-shrink: 0; /* Mencegah icon gepeng saat teks panjang */
        }

        .stats-icon i {
            font-size: 1.5rem;
            line-height: 1; /* Reset line height */
            margin: 0;
            padding: 0;
            display: block;
        }

        /* Tambahan warna orange untuk Barang Masuk */
        .stats-icon.orange { 
            background-color: #fd8d30b8; 
            color: #fd7e14; 
        }

        /* Utilitas untuk membuat 5 kolom sejajar di layar besar */
        @media (min-width: 1200px) {
            .col-lg-2-5 {
                flex: 0 0 auto;
                width: 20%;
            }
        }

        /* Background Colors dengan Transparansi (Soft Look) */
        /* .stats-icon.purple { background-color: #435ebe26; color: #435ebe; }
        .stats-icon.red    { background-color: #dc354526; color: #dc3545; }
        .stats-icon.green  { background-color: #19875426; color: #198754; }
        .stats-icon.blue   { background-color: #0dcaf026; color: #0dcaf0; } */
    </style>
@endpush

@section('main')
<div class="page-heading">
    <h3>Dashboard</h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <!-- Row Widget Utama -->
            <div class="row">
                <!-- Total Barang -->
                <div class="col-6 col-md-4 col-lg-2-5"> <!-- Gunakan custom class atau col-lg-2 jika ingin 5 kolom sejajar -->
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon purple d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted font-semibold">Barang</h6>
                                    <h6 class="font-extrabold mb-0">{{ $total_barang }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stok Menipis -->
                <div class="col-6 col-md-4 col-lg-2-5">
                    <div class="card border border-danger">
                        <div class="card-body px-3 py-4-5">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon red d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted font-semibold">Menipis</h6>
                                    <h6 class="font-extrabold mb-0 text-danger">{{ $stock_menipis }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Masuk (BARU) -->
                <div class="col-6 col-md-4 col-lg-2-5">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon orange d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted font-semibold">Masuk</h6>
                                    <h6 class="font-extrabold mb-0">{{ $barang_masuk }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Keluar -->
                <div class="col-6 col-md-4 col-lg-2-5">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon blue d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-truck-ramp-box"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted font-semibold">Keluar</h6>
                                    <h6 class="font-extrabold mb-0">{{ $barang_keluar }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opname -->
                <div class="col-6 col-md-4 col-lg-2-5">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon green d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted font-semibold">Opname</h6>
                                    <h6 class="font-extrabold mb-0">{{ $opname_bulan }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Barang Stok Menipis -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i> Daftar Barang Stok Menipis</h4>
                            <span class="badge bg-light-danger">Batas Stok <= 5</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover w-100" id="table-stock-menipis">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th width="10%">Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Dashboard (Aktivitas Terbaru) -->
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4>Menu Cepat</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('barang-masuk.create') }}" class="btn btn-success text-start">
                            <i class="fa-solid fa-plus-square me-2"></i> Input Barang Masuk
                        </a>
                        <a href="{{ route('barang-keluar.create') }}" class="btn btn-danger text-start">
                            <i class="bi bi-box-arrow-right"></i> Input Barang Keluar
                        </a>
                        <a href="{{ route('stock-opname.create') }}" class="btn btn-primary text-start">
                            <i class="bi bi-clipboard-check"></i> Stock Opname Baru
                        </a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Notifikasi Stok</h4>
                </div>
                <div class="card-body">
                    @if($stock_menipis > 0)
                        <div class="alert alert-light-danger color-danger">
                            <i class="bi bi-exclamation-circle"></i> Ada {{ $stock_menipis }} barang dengan stock hampir habis!
                        </div>
                    @else
                        <p class="text-muted">Stok barang aman.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-stock-menipis').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('home') }}", // Sesuaikan dengan route dashboard Anda
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'kode', name: 'kode'},
                {data: 'nama', name: 'nama'},
                {data: 'satuan.satuan', name: 'satuan'},
                {data: 'stock', name: 'stock', className: 'text-center'},
            ],
            language: {
                emptyTable: "Hebat! Tidak ada stock yang menipis saat ini.",
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            },
            dom: 'tp', // Hanya tampilkan table dan pagination agar dashboard tetap clean
            pageLength: 5
        });
    });
</script>
@endpush