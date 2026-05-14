@extends('layouts.app')

@section('title', 'Buat Stock Opname')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection { height: calc(3.5rem + 2px); }
        .text-danger-custom { color: #dc3545; font-weight: bold; }
        .text-success-custom { color: #198754; font-weight: bold; }
    </style>
@endpush

@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Stock Opname</h3>
                    <p class="text-subtitle text-muted">Penyesuaian stok sistem dengan stok fisik gudang.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('stock-opname.index') }}">Stock Opname</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="page-content">
        <form action="{{ route('stock-opname.store') }}" method="POST">
            @csrf
            <div class="row">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Data Header -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label>No. Opname</label>
                                <input type="text" name="no_opname" class="form-control" value="SO-{{ date('YmdHis') }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label>Tanggal Opname</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Opname Rutin Bulanan" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Bulk Input --}}
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">Bulk Input (Excel)</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted text-small">Gunakan Excel jika item yang di-opname berjumlah banyak.</p>
                            <div class="d-grid gap-2">
                                <!-- Tombol Download Template -->
                                <a href="{{ route('stock-opname.export-template') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download"></i> 1. Download Daftar Barang
                                </a>
                                
                                <hr>
                                
                                <!-- Form Input File Import -->
                                <label for="import_excel" class="form-label text-small fw-bold">2. Upload File Excel</label>
                                <input type="file" id="import_excel" class="form-control form-control-sm" accept=".xlsx, .xls">
                                <button type="button" id="btn-import" class="btn btn-info btn-sm mt-2">
                                    <i class="bi bi-upload"></i> Proses & Masukkan ke Tabel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Detail Opname -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pilih Barang untuk Diperiksa</h4>
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

                            <div class="table-responsive">
                                <table class="table table-bordered mt-3" id="table-detail">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th width="250">Catatan</th>
                                            <th width="100">Sistem</th>
                                            <th width="120">Fisik</th>
                                            <th width="100">Selisih</th>
                                            <th width="50">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Baris muncul di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">* Selisih = Fisik - Sistem</small>
                            <button type="submit" class="btn btn-success px-4">Finalisasi Opname</button>
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
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Select2 dengan AJAX (Sama dengan referensi Anda)
            $('#search-barang').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari barang...',
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
                                    stok_sekarang: item.stock
                                }
                            })
                        };
                    }
                }
            });

            // 2. Tambah Baris
            $('#btn-tambah-list').click(function() {
                let data = $('#search-barang').select2('data')[0];
                if (!data) return alert('Pilih barang!');

                let exists = false;
                $('input[name="barang_id[]"]').each(function() {
                    if ($(this).val() == data.id) exists = true;
                });
                if (exists) return alert('Barang sudah ada!');

                let row = `
                    <tr>
                        <td>
                            <input type="hidden" name="barang_id[]" value="${data.id}">
                            ${data.text}
                        </td>
                        <td>
                            <input type="text" name="catatan[]" class="form-control form-control-sm stok-sistem" value="">
                        </td>
                        <td>
                            <input type="number" name="stok_sistem[]" class="form-control-plaintext stok-sistem" value="${data.stok_sekarang}" readonly>
                        </td>
                        <td>
                            <input type="number" name="stok_fisik[]" class="form-control form-control-sm stok-fisik" value="${data.stok_sekarang}" min="0" required>
                        </td>
                        <td class="selisih-tampil fw-bold">0</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;

                $('#table-detail tbody').append(row);
                $('#search-barang').val(null).trigger('change');
            });

            // 3. Hitung Selisih Otomatis
            $(document).on('input', '.stok-fisik', function() {
                let row = $(this).closest('tr');
                let sistem = parseFloat(row.find('.stok-sistem').val()) || 0;
                let fisik = parseFloat($(this).val()) || 0;
                let selisih = fisik - sistem;

                let display = row.find('.selisih-tampil');
                display.text(selisih);

                // Warna Indikator
                if(selisih < 0) {
                    display.removeClass('text-success').addClass('text-danger');
                } else if(selisih > 0) {
                    display.removeClass('text-danger').addClass('text-success');
                } else {
                    display.removeClass('text-danger text-success');
                }
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            // 4. Import Excel to Tabel
            $('#btn-import').click(function() {
                let fileInput = document.getElementById('import_excel');
                if (fileInput.files.length === 0) return alert('Pilih file Excel terlebih dahulu!');

                let file = fileInput.files[0];
                let reader = new FileReader();

                reader.onload = function(e) {
                    let data = new Uint8Array(e.target.result);
                    let workbook = XLSX.read(data, { type: 'array' });
                    let firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    
                    // Konversi sheet ke JSON
                    let jsonData = XLSX.utils.sheet_to_json(firstSheet);

                    if (jsonData.length === 0) return alert('File Excel kosong atau format salah!');

                    // Kosongkan tabel sebelum import (opsional)
                    // $('#table-detail tbody').empty();

                    jsonData.forEach(function(row) {
                        // Asumsi kolom Excel: id_barang, nama_barang, stok_sistem, stok_fisik
                        let id = row.id_barang || row.ID;
                        let nama = row.nama_barang || row.Nama;
                        let catatan = row.catatan || row.Catatan;
                        let sistem = row.stok_sistem || row.Sistem || 0;
                        let fisik = row.stok_fisik || row.Fisik || 0;
                        let selisih = fisik - sistem;

                        // Cek duplikasi
                        let exists = false;
                        $('input[name="barang_id[]"]').each(function() {
                            if ($(this).val() == id) exists = true;
                        });

                        if (!exists) {
                            let colorClass = selisih < 0 ? 'text-danger' : (selisih > 0 ? 'text-success' : '');
                            
                            let htmlRow = `
                                <tr>
                                    <td>
                                        <input type="hidden" name="barang_id[]" value="${id}">
                                        ${nama}
                                    </td>
                                    <td>
                                        <input type="text" name="catatan[]" class="form-control form-control-sm stok-sistem" value="${catatan}">
                                    </td>
                                    <td>
                                        <input type="number" name="stok_sistem[]" class="form-control-plaintext" value="${sistem}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="stok_fisik[]" class="form-control form-control-sm stok-fisik" value="${fisik}" min="0" required>
                                    </td>
                                    <td class="selisih-tampil fw-bold ${colorClass}">${selisih}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            `;
                            $('#table-detail tbody').append(htmlRow);
                        }
                    });

                    alert('Data berhasil diimpor ke tabel!');
                    $('#import_excel').val(''); // Reset input file
                };

                reader.readAsArrayBuffer(file);
            });
        });
    </script>
@endpush