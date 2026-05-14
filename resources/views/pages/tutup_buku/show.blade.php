@extends('layouts.app')
@section('title', 'Detail Barang Masuk')
@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $pageTitle }}</h3>
                    <p class="text-subtitle text-muted">Halaman untuk melihat detail tutup buku.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tutup-buku.index') }}">Tutup Buku</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Laporan Saldo Barang - {{ $namaBulan }} {{ $tahun }}</h4>
                <a href="{{ route('tutup-buku.index') }}" class="btn btn-outline-secondary"> <i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    {{ $dataTable->table(['class' => 'table table-striped w-100']) }}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    {{ $dataTable->scripts() }}
@endpush