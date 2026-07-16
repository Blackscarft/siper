@extends('layouts.app')

@section('title', 'Tambah Akun')

@section('main')
    <section class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Akun</h3>
                    <p class="text-subtitle text-muted">Halaman untuk mengelola master data user.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Form buat akun</h6>
                        </div>
                        <div class="card-body">
                            <form id="buatAkun" action="{{ route('admin.user.create') }}" method="post"
                                class="needs-validation" enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="alert alert-warning alert-dismissible fade" role="alert" id="alert">
                                    <div id="alertMessage"></div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="form-label">Nama<span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Nama"
                                        value="{{ Cookie::get('name') !== null ? Cookie::get('name') : old('name') }}"
                                        required>
                                    <div class="invalid-feedback">
                                        Nama tidak boleh kosong
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Email"
                                        value="{{ Cookie::get('email') !== null ? Cookie::get('email') : old('email') }}"
                                        required>
                                    <div class="invalid-feedback">
                                        Email tidak boleh kosong dan gunakan format email yang benar
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="form-label">Password<span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="password minimal 8 karakter" minlength="8" required>
                                    <div class="invalid-feedback">
                                        Password tidak boleh kosong dan minimal 8 karakter
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Konfirmasi password<span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="konfirmasi Password" minlength="8" required>
                                    <div class="invalid-feedback">
                                        Konfirmasi password tidak boleh kosong dan harus sama
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="role" class="form-label">Role<span class="text-danger">*</span></label>
                                    <select name="role" id="role" class="form-select" required>
                                        <option value="">- Pilih Role -</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ ucwords(strtolower(str_replace('_', ' ', $role->name))) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Pilih Salah satu
                                    </div>
                                </div>

                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary" id="btnSubmitForm">
                                        <span class="spinner-border spinner-border-sm" id="loader" aria-hidden="true" hidden></span>
                                        <span role="status" id="statusButton">Buat Akun</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Data User</h6>
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    {{ $dataTable->scripts() }}

    {{-- File Uploader --}}
    <script src="https://cdn.jsdelivr.net/npm/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/filepond/dist/filepond.min.js"></script>

        {{-- Handle buat akun --}}
    <script>
        $(document).ready(function() {
            'use strict' // untuk validasi javascript yang lebih sensitif
            $('#alert').hide();
            // mencegah form untuk reload waktu di submit
            $('#buatAkun').submit(function(e) {
                e.preventDefault();
            });

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
                $('#statusButton').text('Buat Akun');
            }

            function onErrorHandling() {
                // Lakukan tindakan atau tampilkan pesan kesalahan sesuai kebutuhan
                // Atau lakukan tindakan lain untuk menangani kesalahan
                alert("Maaf, terjadi kesalahan. Silakan coba lagi.");
            }

            function sendData(form) {
                let url = "{{ route('admin.user.create') }}";
                let formData = new FormData(form);

                $.ajax({
                    url: url,
                    method: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        onLoading();
                    },
                    success: function(res) {
                        onSuccess(); // reset button
                        // reset form
                        $('#users-table').DataTable().ajax.reload();
                        $('#buatAkun')[0].reset();

                        // reset class form
                        $('form').removeClass('was-validated');

                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        })
                    },
                    error: function(error) {
                        if (error.status === 422) {
                            onSuccess(); // reset button
                            let errors = error.responseJSON.errors;

                            $('#alert').show();
                            $('#alert').addClass('show');
                            let alertMessage = $('#alertMessage');
                            alertMessage.empty();

                            // jika error dari server
                            $.each(errors, function(key, errorArray) {
                                $.each(errorArray, function(index, errorMessage) {
                                    let li = $('<li>').addClass('error').text(
                                        errorMessage);
                                    alertMessage.append(
                                        li
                                    ); // Menambahkan pesan kesalahan ke dalam kontainer
                                });
                            });
                        } else {
                            // Fungsi untuk menangani kesalahan
                            onErrorHandling();
                            onSuccess(); // reset button
                        }
                    }
                });
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
                            console.log('oke');
                            sendData(form)
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            }
            handleFormSubmission();
        });
    </script>

@endpush