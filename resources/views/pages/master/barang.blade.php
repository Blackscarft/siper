@extends('layouts.app')

@section('title', 'Barang')

@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Barang</h3>
                    <p class="text-subtitle text-muted">Halaman untuk mengelola master data barang.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Barang</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section class="page-content">
        <div class="container-fluid">
            <div class="row">
                {{-- Role manager tidak dapat menambah barang --}}
                @unless(auth()->user()->hasRole('manager')) 
                <div class="col-4">
                    <div class="card">
                        <form id="formBarang" class="needs-validation" data-parsley-validate>
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 id="form-header">Form Tambah Barang</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        @csrf
                                        <input type="hidden" id="barang_id">
                                        <div class="form-group mandatory nama_container">
                                            <label for="nama-column" class="form-label">Nama Barang</label>
                                            <input type="text" name="nama" id="nama-column" class="form-control"
                                                placeholder="Nama Barang"
                                                value="{{ Cookie::get('nama') !== null ? Cookie::get('nama') : old('nama') }}"
                                                data-parsley-required="true">
                                            <ul class="parsley-error filled p-0 nama_hidden" hidden>
                                                <span class="parsley-required nama_error_message"></span>
                                            </ul>
                                        </div>
                                        <div class="form-group mandatory satuan_container">
                                            <label for="satuan-select" class="form-label">Satuan Barang</label>
                                            <select name="satuan" id="satuan-select" class="form-select"
                                                data-parsley-required="true">
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                                                @endforeach
                                            </select>
                                            <ul class="parsley-error filled p-0 satuan_hidden" hidden>
                                                <span class="parsley-required satuan_error_message"></span>
                                            </ul>
                                        </div>
                                        <div class="form-group mandatory kategori_container">
                                            <label for="kategori-select" class="form-label">Kategori Barang</label>
                                            <select name="kategori" id="kategori-select" class="form-select"
                                                data-parsley-required="true">
                                                @foreach ($kategories as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                                @endforeach
                                            </select>
                                            <ul class="parsley-error filled p-0 kategori_hidden" hidden>
                                                <span class="parsley-required kategori_error_message"></span>
                                            </ul>
                                        </div>
                                        <div class="form-group catatan_container">
                                            <label for="catatan-column" class="form-label">Catatan</label>
                                            <textarea name="catatan" id="catatan-column" cols="30" rows="5" class="form-control"></textarea>
                                            <ul class="parsley-error filled p-0 catatan_hidden" hidden>
                                                <span class="parsley-required catatan_error_message"></span>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="form-group float-end mb-3">
                                    <button type="submit" class="btn btn-primary" id="btnSubmitForm">
                                        <span class="spinner-border spinner-border-sm" id="loader" aria-hidden="true"
                                            hidden></span>
                                        <span role="status" id="statusButton">Simpan</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-cancel"
                                        onclick="defaultForm()">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endunless
                <div class="{{ auth()->user()->hasRole('manager') ? 'col-12' : 'col-8' }}">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Data Barang</h6>
                            <a href="{{ route('barang.export') }}" type="button" class="btn btn-success btn-sm" id="exportBtn"> <i class="fas fa-file-export"></i> Export</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{ $dataTable->table() }}
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

    @unless(auth()->user()->hasRole('manager'))
    <script>
        'use strict';

        function onLoading() {
            // Menambahkan properti disabled pada tombol submit
            $('#btnSubmitForm').prop('disabled', true);
            // Menghapus properti hidden pada spinner
            $('#loader').removeAttr('hidden');
            // Mengubah teks di statusButton menjadi "Loading..."
            $('#statusButton').text('Loading...');
        }

        function onSuccess() {
            // Kembalikan properti disabled pada tombol submit
            $('#btnSubmitForm').removeAttr('disabled');
            // Tambahkan kembali properti hidden pada spinner
            $('#loader').attr('hidden', true);
            // Kembalikan teks di statusButton menjadi "Buat Akun"
            $('#statusButton').text('Simpan');
        }

        function defaultForm() {
            onSuccess();
            $('#form-header').html('Form Tambah Barang');
            $('.btn-cancel').hide();
            $('#formBarang')[0].reset();
            $('#barang_id').val('');
            // reset class form
            $('form').removeClass('was-validated');
        }        

        function onErrorHandling() {
            // Lakukan tindakan atau tampilkan pesan kesalahan sesuai kebutuhan
            // Atau lakukan tindakan lain untuk menangani kesalahan
            alert("Maaf, terjadi kesalahan. Silakan coba lagi.");
        }

        function sendData(form) {
            let formData = new FormData(form);

            let data = {
                nama: $('#nama-column').val(),
                satuan: $('#satuan-select').val(),
                kategori: $('#kategori-select').val(),
                catatan: $('#catatan-column').val(),
            }

            let url = '';
            let method = 'POST';
            if ($('#form-header').html() == 'Form Tambah Barang') {
                url = '{{ route('barang.store') }}';
                method = 'POST';
            } else {
                url = '{{ route('barang.update', ['barang' => ':id']) }}';
                url = url.replace(':id', $('#barang_id').val());
                method = 'PUT';
                data = {
                    id: $('#barang_id').val(),
                    ...data,
                }
            }

            $.ajax({
                url: url,
                method: method,
                data: data,
                datatype: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    onLoading();
                },
                success: function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    })

                    defaultForm();
                    $('#formBarang')[0].reset();
                    // reset class form
                    $('form').removeClass('was-validated');
                    $('#barangs-table').DataTable().ajax.reload();
                },
                error: function(error) {
                    onSuccess(); // reset button
                    if (error.status === 422) {
                        let errors = error.responseJSON.errors;
                        $('form').removeClass('was-validated');
                        if (errors.nama) {
                            $('.nama_container').addClass('is-invalid')
                            $('.nama_hidden').removeAttr('hidden')
                            $('.nama_error_message').text(errors.nama[0])
                        }
                        if (errors.satuan) {
                            $('.satuan_container').addClass('is-invalid')
                            $('.satuan_hidden').removeAttr('hidden')
                            $('.satuan_error_message').text(errors.satuan[0])
                        }
                        if (errors.kategori) {
                            $('.kategori_container').addClass('is-invalid')
                            $('.kategori_hidden').removeAttr('hidden')
                            $('.kategori_error_message').text(errors.kategori[0])
                        }
                    } else {
                        // Fungsi untuk menangani kesalahan
                        onErrorHandling();
                    }
                }
            });
        }

        function getData(id) {
            let url = '{{ route('barang.show', ['barang' => ':id']) }}'
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                cache: true,
                method: 'GET',
                datatype: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                beforeSend: function() {
                    if (!$('.form-header').html() == '') {
                        defaultForm()
                    }
                },
                success: function(res) {
                    $('#barang_id').val(res.id);
                    $('#nama-column').val(res.nama);
                    $('#satuan-select').val(res.satuan_id);
                    $('#kategori-select').val(res.kategori_id);
                    $('#catatan-column').val(res.catatan);

                    $('#form-header').html('Form Edit Barang');
                    $('.btn-cancel').show();
                    $('.btn-cancel').removeAttr('hidden');
                },
            })
        }

        function remove(id) {
            let url = '{{ route('barang.destroy', ['barang' => ':id']) }}';
            url = url.replace(':id', id);

            Swal.fire({
                title: "Anda yakin akan menghapus data barang ini?",
                html: 'Semua data dari barang ini akan terpengaruh',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#435ebe',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        success: function(response) {
                            $('#barangs-table').DataTable().ajax.reload();
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            )
                        }
                    });
                }
            })
        }

        function handleFormSubmission() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')
            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        sendData(form)
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        }

        $('#formBarang').submit(function(e) {
            e.preventDefault();
        });

        defaultForm();
        handleFormSubmission();
    </script>
    @endunless
    
@endpush