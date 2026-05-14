@extends('layouts.app')

@section('title', 'Detail Stock Opname - ' . $opname->no_opname)

@section('main')
<section class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Stock Opname</h3>
                <p class="text-subtitle text-muted">Informasi lengkap penyesuaian stok barang.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="{{ route('stock-opname.index') }}" class="btn btn-secondary me-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('stock-opname.pdf', $opname->id) }}" target="_blank" class="btn btn-danger">
                    <i class="bi bi-printer"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="row">
        <!-- Informasi Header -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Dokumen</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">No. Dokumen</span>
                            <strong>{{ $opname->no_opname }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Tanggal</span>
                            <strong>{{ \Carbon\Carbon::parse($opname->tanggal)->format('d/m/Y') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Petugas</span>
                            <strong>{{ $opname->admin->name }}</strong>
                        </li>
                    </ul>
                    <div class="mt-3">
                        <label class="text-muted small">Keterangan:</label>
                        <p class="mb-0">{{ $opname->keterangan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Detail Barang -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Barang & Selisih</h4>
                    <span class="badge bg-primary">{{ $opname->details->count() }} Item</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-show-detail">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Sistem</th>
                                    <th>Fisik</th>
                                    <th>Selisih</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opname->details as $detail)
                                <tr>
                                    <td><code>{{ $detail->barang->kode }}</code></td>
                                    <td>{{ $detail->barang->nama }}</td>
                                    <td class="text-center">{{ $detail->stok_sistem }}</td>
                                    <td class="text-center font-bold">{{ $detail->stok_fisik }}</td>
                                    <td class="text-center">
                                        @if($detail->selisih > 0)
                                            <span class="badge bg-success">+{{ $detail->selisih }}</span>
                                        @elseif($detail->selisih < 0)
                                            <span class="badge bg-danger">{{ $detail->selisih }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $detail->catatan ?? '-' }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-show-detail').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Cari barang dalam dokumen ini:"
                }
            });
        });
    </script>
@endpush