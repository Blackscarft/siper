@extends('layouts.app')
@section('title', 'Detail Barang Keluar')
@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Transaksi Barang Keluar</h3>
                    <p class="text-subtitle text-muted">Halaman untuk melihat detail transaksi barang keluar.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('barang-keluar.index') }}">Barang Keluar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary mb-0">
                <i class="bi bi-receipt me-2"></i>Detail Transaksi # {{ $barangKeluar->no_transaksi }}
            </h4>
            <a href="{{ route('barang-keluar.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <!-- Header Informasi -->
                <div class="row g-3">
                    <div class="col-md-3 border-end">
                        <label class="text-muted d-block small uppercase">Tanggal Keluar</label>
                        <span class="fw-bold">{{ $barangKeluar->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="col-md-3 border-end">
                        <label class="text-muted d-block small uppercase">Admin Pelaksana</label>
                        <span class="fw-bold">{{ $barangKeluar->admin->name ?? 'System' }}</span>
                    </div>
                    <div class="col-md-2 border-end">
                        <label class="text-muted d-block small uppercase">Total Item</label>
                        <span class="badge bg-primary fs-6">{{ $barangKeluar->details_count }} Barang</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted d-block small uppercase">Keterangan</label>
                        <span class="text-secondary">{{ $barangKeluar->keterangan ?? '-' }}</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Bagian Tabel -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Daftar Barang Keluar</h5>
                    <!-- Tombol Cetak di Atas Tabel -->
                    <div class="btn-group">
                        <a target="_blank" href="{{ route('barang-keluar.pdf', $barangKeluar->id) }}" class="btn btn-outline-success">
                            <i class="bi bi-file-earmark-pdf me-2"></i> PDF
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="15%">Kode Barang</th>
                                <th width="40%">Nama Barang</th>
                                <th width="20%">Kategori</th>
                                <th class="text-center" width="10%">Qty</th>
                                <th width="10%">Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangKeluar->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $detail->barang->kode }}</span></td>
                                <td class="fw-medium">{{ $detail->barang->nama }}</td>
                                <td>{{ $detail->barang->kategori->kategori ?? '-' }}</td>
                                <td class="text-center fw-bold">{{ $detail->jumlah }}</td>
                                <td>{{ $detail->barang->satuan->satuan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">TOTAL KESELURUHAN</td>
                                <td class="text-center">{{ $barangKeluar->details->sum('jumlah') }}</td>
                                <td>Unit</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <small class="text-muted italic">
                    * Transaksi ini dibuat secara otomatis oleh sistem pada {{ $barangKeluar->created_at->format('d/m/Y H:i') }}
                </small>
            </div>
        </div>
    </section>
@endsection