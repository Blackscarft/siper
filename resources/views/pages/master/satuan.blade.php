@extends('layouts.app')

@section('title', 'Satuan Barang')

@push('style')
    
@endpush

@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Satuan Barang</h3>
                    <p class="text-subtitle text-muted">Halaman untuk mengelola master data satuan barang.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Satuan Barang</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <form id="formSatuanBarang" class="needs-validation" data-parsley-validate>
                            <div class="card-header">
                                <h6 id="form-header">Form Tambah Satuan Barang</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        @csrf
                                        <input type="hidden" id="satuan_id">
                                        <div class="form-group mandatory satuan_container">
                                            <label for="satuan-column" class="form-label">Satuan</label>
                                            <input type="text" name="satuan" id="satuan-column" class="form-control"
                                                placeholder="Satuan"
                                                value="{{ Cookie::get('satuan') !== null ? Cookie::get('satuan') : old('satuan') }}"
                                                data-parsley-required="true">
                                            <ul class="parsley-error filled p-0 satuan_hidden" hidden>
                                                <span class="parsley-required satuan_error_message"></span>
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
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h6>Data Satuan Barang</h6>
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
            $('#form-header').html('Form Tambah Satuan Barang');
            $('.btn-cancel').hide();
            $('#formSatuanBarang')[0].reset();
            $('#satuan_id').val('');
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
                satuan: $('#satuan-column').val(),
            }

            let url = '';
            let method = 'POST';
            if ($('#form-header').html() == 'Form Tambah Satuan Barang') {
                url = '{{ route('satuan.store') }}';
                method = 'POST';
            } else {
                url = '{{ route('satuan.update', ['satuan' => ':id']) }}';
                url = url.replace(':id', $('#satuan_id').val());
                method = 'PUT';
                data = {
                    id: $('#satuan_id').val(),
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
                    $('#formSatuanBarang')[0].reset();
                    // reset class form
                    $('form').removeClass('was-validated');
                    $('#satuanbarang-table').DataTable().ajax.reload();
                },
                error: function(error) {
                    onSuccess(); // reset button
                    if (error.status === 422) {
                        let errors = error.responseJSON.errors;
                        $('form').removeClass('was-validated');
                        if (errors.satuan) {
                            $('.satuan_container').addClass('is-invalid')
                            $('.satuan_hidden').removeAttr('hidden')
                            $('.satuan_error_message').text(errors.satuan[0])
                        }
                    } else {
                        // Fungsi untuk menangani kesalahan
                        onErrorHandling();
                    }
                }
            });
        }

        function getData(id) {
            let url = '{{ route('satuan.show', ['satuan' => ':id']) }}'
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
                    $('#satuan_id').val(res.id);
                    $('#satuan-column').val(res.satuan);

                    $('#form-header').html('Form Edit Satuan Barang');
                    $('.btn-cancel').show();
                    $('.btn-cancel').removeAttr('hidden');
                },
            })
        }

        function remove(id) {
            let url = '{{ route('satuan.destroy', ['satuan' => ':id']) }}';
            url = url.replace(':id', id);

            Swal.fire({
                title: "Anda yakin akan menghapus data master ini?",
                html: 'Semua data dari satuan ini akan terpengaruh',
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
                            $('#satuanbarang-table').DataTable().ajax.reload();
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

        $('#formSatuanBarang').submit(function(e) {
            e.preventDefault();
        });

        defaultForm();
        handleFormSubmission();
    </script>
@endpush