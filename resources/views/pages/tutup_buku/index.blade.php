@extends('layouts.app')

@section('title', 'Tutup Buku')

@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tutup Buku</h3>
                    <p class="text-subtitle text-muted">Halaman untuk mengelola tutup buku.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tutup Buku</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section class="page-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Kolom Form Eksekusi -->
                @unless(auth()->user()->hasRole('manager'))
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Tutup Buku</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Info:</strong> Pastikan semua stok opname bulan lalu sudah selesai diinput.
                            </div>

                            <form action="{{ route('tutup-buku.store') }}" method="POST" id="formTutupBuku">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="bulan">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-select @error('bulan') is-invalid @enderror">
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ date('m', strtotime('last month')) == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tahun">Tahun</label>
                                    <select name="tahun" id="tahun" class="form-select @error('tahun') is-invalid @enderror">
                                        @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="confirm" required>
                                    <label class="form-check-label text-small" for="confirm">
                                        Saya setuju untuk mengunci data transaksi pada periode tersebut.
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                                    <i class="bi bi-lock-fill"></i> Jalankan Tutup Buku
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endunless

                <!-- Kolom Riwayat (DataTables) -->
                <div class="{{ auth()->user()->hasRole('manager') ? 'col-12' : 'col-8' }}">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Riwayat Tutup Buku</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{ $dataTable->table(['class' => 'table table-striped w-100']) }}
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
@endsection

@push('script')
    <!-- Yajra DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    {{ $dataTable->scripts() }}
@endpush