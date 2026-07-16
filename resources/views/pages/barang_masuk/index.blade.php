@extends('layouts.app')

@section('title', 'Barang Masuk')

@push('style')
@endpush

@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Barang Masuk</h3>
                    <p class="text-subtitle text-muted">Transaksi penerimaan barang ke gudang.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('barang-masuk.index') }}">Barang Masuk</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section>

        <div class="col-12">
            {{-- Filter --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-funnel"></i> Filter Data
                    </h6>
                </div>

                <div class="card-body">
                    <form id="formFilter">
                        <div class="row align-items-end">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    Dari Tanggal
                                </label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="tanggal_awal"
                                    name="tanggal_awal">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    Sampai Tanggal
                                </label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="tanggal_akhir"
                                    name="tanggal_akhir">
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="d-flex justify-content-md-end gap-2">

                                    <button
                                        type="button"
                                        id="btn-filter"
                                        class="btn btn-primary">
                                        <i class="bi bi-search"></i>
                                        Terapkan
                                    </button>

                                    <button
                                        type="reset"
                                        id="btn-reset"
                                        class="btn btn-light border">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        Reset
                                    </button>

                                    <button
                                        disabled
                                        hidden
                                        type="button"
                                        id="btn-export"
                                        class="btn btn-outline-success">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        Export PDF
                                    </button>

                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6>Data Barang Masuk</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {{ $dataTable->table() }}
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

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000, // Menghilang dalam 3 detik
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    <script>
        $('#btn-filter').on('click', function() {
            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();

            if (!tanggal_awal || !tanggal_akhir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Silakan pilih tanggal awal dan tanggal akhir sebelum menerapkan filter.',
                    showConfirmButton: true,
                });
                return;
            }

            if (tanggal_awal > tanggal_akhir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.',
                    showConfirmButton: true,
                });
                return;
            }

            $('#btn-export').prop('disabled', false);
            $('#btn-export').removeAttr('hidden');

            $('#barangmasuk-table').DataTable().ajax.url('{{ route('barang-masuk.index') }}?tanggal_awal=' + tanggal_awal + '&tanggal_akhir=' + tanggal_akhir).load();
        });

        $('#btn-reset').on('click', function() {
            $('#formFilter')[0].reset();
            $('#btn-export').prop('disabled', true);
            $('#btn-export').attr('hidden', true);
            $('#barangmasuk-table').DataTable().ajax.url('{{ route('barang-masuk.index') }}').load();
        });

        $('#btn-export').click(function () {

            let tanggal_awal  = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();

            if (!tanggal_awal || !tanggal_akhir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Silakan pilih tanggal awal dan tanggal akhir sebelum mengekspor data.',
                    showConfirmButton: true,
                });
                return;
            }

            if (tanggal_awal > tanggal_akhir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.',
                    showConfirmButton: true,
                });
                return;
            }

        const url = "{{ route('barang-masuk.pdf.range') }}" +
            "?start=" + encodeURIComponent(tanggal_awal) +
            "&end=" + encodeURIComponent(tanggal_akhir);

        window.open(url, '_blank');
        });
    </script>
@endpush