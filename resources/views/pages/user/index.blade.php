@extends('layouts.app')

@section('title', 'Akun Profile')

@push('style')
    {{--  --}}
@endpush

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Profil</h3>
                    <p class="text-subtitle text-muted">Halaman untuk mengubah data informasi akun</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profil</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <section class="section mb-5">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <img id="photo-profile"
                        src="{{ Auth::user()->url_photo ? asset('profile/' . Auth::user()->id . '/' . Auth::user()->url_photo) : asset('jpg/1.jpg') }}"
                        width="200px" class="rounded-circle center-cropped" alt="">
                </div>
                <div class="text-center pt-3">
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-photo">
                        <i class="bi bi-pencil"></i> Ubah Foto
                    </button>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form method="post" id="changeProfile" class="needs-validation-updateProfile" data-parsley-validate>
                        @csrf
                        <div class="card-body">
                            <div class="col-12" id="alertErrorProfile">
                                {{-- generate --}}
                            </div>
                            <div class="form-group mandatory nama_container">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $user->name }}" data-parsley-required="true">
                                <ul class="parsley-error filled p-0 nama_hidden" hidden>
                                    <span class="parsley-required nama_error_message"></span>
                                </ul>
                            </div>

                            <div class="form-group mandatory email_container">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}" data-parsley-required="true">
                                <ul class="parsley-error filled p-0 email_hidden" hidden>
                                    <span class="parsley-required email_error_message"></span>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group d-flex justify-content-end m-0">
                                <button type="submit" class="btn btn-primary" id="btnSubmitFormProfile">
                                    <span class="spinner-border spinner-border-sm" id="loaderProfile" aria-hidden="true"
                                        hidden></span>
                                    <span role="status" id="statusButtonProfile">Simpan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Ubah Password</h5>
                    </div>
                    <form method="post" id="chagePassword" class="needs-validation-updatePassword" data-parsley-validate>
                        @csrf
                        <div class="card-body">
                            <div class="col-12" id="alertErrorPassword">
                                {{-- generate --}}
                            </div>
                            <div class="form-group my-2 mandatory password_now_container">
                                <label for="current_password" class="form-label">Password sekarang</label>
                                <input type="password" name="current_password" id="current_password" class="form-control"
                                    placeholder="Masukan password sekarang" data-parsley-required="true">
                                <ul class="parsley-error filled p-0 password_now_hidden" hidden>
                                    <span class="parsley-required password_now_error_message"></span>
                                </ul>
                            </div>
                            <div class="form-group my-2 mandatory password_container">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password baru" minlength="8" data-parsley-required="true">
                                <ul class="parsley-error filled p-0 password_hidden" hidden>
                                    <span class="parsley-required password_error_message"></span>
                                </ul>
                            </div>
                            <div class="form-group my-2 mandatory confirm_password_container">
                                <label for="password_confirmation" class="form-label">Konfirmasi password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="konfirmasi Password" minlength="8"
                                    data-parsley-required="true">
                                <ul class="parsley-error filled p-0 confirm_password_hidden" hidden>
                                    <span class="parsley-required confirm_password_error_message"></span>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group d-flex justify-content-end m-0">
                                <button type="submit" class="btn btn-primary" id="btnSubmitFormPassword">
                                    <span class="spinner-border spinner-border-sm" id="loaderPassword" aria-hidden="true"
                                        hidden></span>
                                    <span role="status" id="statusButtonPassword">Simpan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-photo" tabindex="-1" aria-labelledby="modal-photoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-imunisasiLabel">Ubah Foto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formPhoto" enctype="multipart/form-data" class="needs-validation"
                        data-parsley-validate>
                        @csrf
                        <div class="row mb-3" id="image-container" hidden>
                            <div class="col-12 h-75 d-flex justify-content-center">
                                <img id="output" class="img-fluid">
                            </div>
                        </div>
                        <div class="form-group mandatory photo_container">
                            <label for="photo-upload" class="form-label">Foto</label>
                            <input type="file" accept=".jpg, .png, .jpeg" name="photo" id="photo-upload"
                                class="form-control"
                                value="{{ Cookie::get('photo') !== null ? Cookie::get('photo') : old('photo') }}"
                                data-parsley-required="true">
                            <ul class="parsley-error filled p-0 photo_hidden" hidden>
                                <span class="parsley-required photo_error_message"></span>
                            </ul>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-cancel-deposit"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitFormPhoto">
                        <span class="spinner-border spinner-border-sm" id="loaderPhoto" aria-hidden="true" hidden></span>
                        <span role="status" id="statusButtonPhoto">Simpan</span>
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'
            $('#alert').hide();

            const formsProfile = document.querySelectorAll('.needs-validation-updateProfile');
            // Loop over them and prevent submission
            Array.from(formsProfile).forEach(form => {
                form.addEventListener('submit', event => {
                    let fileSizeInMb = 0;
                    const fileMaxSizeInMb = 1;

                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        event.preventDefault()
                        event.stopPropagation()
                        updateProfile(form);
                    }

                    form.classList.add('was-validated')
                }, false)
            })

            // Form Change Profile
            function onLoadingProfile() {
                // Menambahkan properti disabled pada tombol submit
                $('#btnSubmitFormProfile').prop('disabled', true);
                // Menghapus properti hidden pada spinner
                $('#loaderProfile').removeAttr('hidden');
                // Mengubah teks di statusButton menjadi "Loading..."
                $('#statusButtonProfile').text('Loading...');
            }

            function onSuccessProfile() {
                // Kembalikan properti disabled pada tombol submit
                $('#btnSubmitFormProfile').removeAttr('disabled');
                // Tambahkan kembali properti hidden pada spinner
                $('#loaderProfile').attr('hidden', true);
                // Kembalikan teks di statusButtonProfile menjadi "Buat Akun"
                $('#statusButtonProfile').text('Simpan');
            }

            function defaultFormProfile() {
                onSuccessProfile();
                // reset class form
                $('form').removeClass('was-validated');
            }

            defaultFormProfile();

            function updateProfile(form) {
                let url = "{{ route('admin.user.updateProfile') }}";
                let formData = new FormData(form);
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        onLoadingProfile()
                    },
                    success: function(res) {
                        defaultFormProfile();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        })

                        $('#profileName').text(res.name);
                        $('#changeProfile').removeClass('was-validated');
                    },
                    error: function(error) {
                        onSuccessProfile(); // reset button
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            $('form').removeClass('was-validated');
                            if (errors.nama) {
                                $('.nama_container').addClass('is-invalid')
                                $('.nama_hidden').removeAttr('hidden')
                                $('.nama_error_message').text(errors.nama[0])
                            }
                            if (errors.email) {
                                $('.email_container').addClass('is-invalid')
                                $('.email_hidden').removeAttr('hidden')
                                $('.email_error_message').text(errors.email[0])
                            }
                        } else {
                            // Fungsi untuk menangani kesalahan
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan di sistem',
                                'error'
                            )
                        }
                    }
                });
            }

            /*
                Handle Update password
            */
            const formsPassword = document.querySelectorAll('.needs-validation-updatePassword');
            // Loop over them and prevent submission
            Array.from(formsPassword).forEach(form => {
                form.addEventListener('submit', event => {
                    let fileSizeInMb = 0;
                    const fileMaxSizeInMb = 1;

                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        event.preventDefault()
                        event.stopPropagation()
                        updatePassword(form);
                    }

                    form.classList.add('was-validated')
                }, false)
            })


            // Update Password
            function onLoadingPassword() {
                // Menambahkan properti disabled pada tombol submit
                $('#btnSubmitFormPassword').prop('disabled', true);
                // Menghapus properti hidden pada spinner
                $('#loaderPassword').removeAttr('hidden');
                // Mengubah teks di statusButton menjadi "Loading..."
                $('#statusButtonPassword').text('Loading...');
            }

            function onSuccessPassword() {
                // Kembalikan properti disabled pada tombol submit
                $('#btnSubmitFormPassword').removeAttr('disabled');
                // Tambahkan kembali properti hidden pada spinner
                $('#loaderPassword').attr('hidden', true);
                // Kembalikan teks di statusButtonPassword menjadi "Buat Akun"
                $('#statusButtonPassword').text('Simpan');
            }

            function defaultFormPassword() {
                onSuccessPassword();
                // reset class form
                $('form').removeClass('was-validated');
            }

            defaultFormPassword();

            function updatePassword(form) {
                let url = "{{ route('admin.user.updatePassword') }}";
                let formData = new FormData(form);
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        onLoadingPassword()
                    },
                    success: function(res) {
                        defaultFormPassword();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        })

                        $('#chagePassword')[0].reset();
                        $('#chagePassword').removeClass('was-validated');
                    },
                    error: function(error) {
                        onSuccessPassword(); // reset button
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            $('form').removeClass('was-validated');
                            if (errors.current_password) {
                                $('.password_now_container').addClass('is-invalid')
                                $('.password_now_hidden').removeAttr('hidden')
                                $('.password_now_error_message').text(errors.current_password[0])
                            }
                            if (errors.password) {
                                $('.password_container').addClass('is-invalid')
                                $('.password_hidden').removeAttr('hidden')
                                $('.password_error_message').text(errors.password[0])
                            }
                            if (errors.password_confirmation) {
                                $('.confirm_password_container').addClass('is-invalid')
                                $('.confirm_password_hidden').removeAttr('hidden')
                                $('.confirm_password_error_message').text(errors.password_confirmation[
                                    0])
                            }
                        } else {
                            // Fungsi untuk menangani kesalahan
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan di sistem',
                                'error'
                            )
                        }
                    }
                });
            }

            // Form Change Photo
            function onLoadingPhoto() {
                // Menambahkan properti disabled pada tombol submit
                $('#btnSubmitFormPhoto').prop('disabled', true);
                // Menghapus properti hidden pada spinner
                $('#loaderPhoto').removeAttr('hidden');
                // Mengubah teks di statusButton menjadi "Loading..."
                $('#statusButtonPhoto').text('Loading...');
            }

            function onSuccessPhoto() {
                // Kembalikan properti disabled pada tombol submit
                $('#btnSubmitFormPhoto').removeAttr('disabled');
                // Tambahkan kembali properti hidden pada spinner
                $('#loaderPhoto').attr('hidden', true);
                // Kembalikan teks di statusButtonPhoto menjadi "Buat Akun"
                $('#statusButtonPhoto').text('Simpan');
            }

            function defaultFormPhoto() {
                onSuccessPhoto();
                // reset class form
                $('#formPhoto')[0].reset();
                $('form').removeClass('was-validated');
            }

            defaultFormPhoto();

            function updatePhoto(form) {
                let url = "{{ route('admin.user.updatePhoto') }}";
                let formData = new FormData(form);
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        onLoadingPhoto()
                    },
                    success: function(res) {
                        $('#modal-photo').modal('hide');
                        $('#photo-profile').attr('src', res.photo);
                        $('#navbar-photo-profile').attr('src', res.photo);
                        $('#image-container').prop('hidden', true);
                        defaultFormPhoto();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        })

                        $('#profileName').text(res.name);
                        $('#changeProfile').removeClass('was-validated');
                    },
                    error: function(error) {
                        onSuccessPhoto(); // reset button
                        if (error.status === 422) {
                            let errors = error.responseJSON.errors;
                            $('form').removeClass('was-validated');
                            if (errors.photo) {
                                $('.photo_container').addClass('is-invalid')
                                $('.photo_hidden').removeAttr('hidden')
                                $('.photo_error_message').text(errors.photo[0])
                            }
                        } else {
                            // Fungsi untuk menangani kesalahan
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan di sistem',
                                'error'
                            )
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const imageInput = document.querySelector('#photo-upload');
                const imgPreview = document.querySelector('#output');
                const notaContainer = document.querySelector('.photo_container');
                const errorElement = document.querySelector('.photo_error_message');
                const notaHidden = document.querySelector('.photo_hidden');

                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.size <= 1024 * 1024) { // File size check for under 1MB
                        notaContainer.classList.remove('is-invalid');
                        notaHidden.setAttribute('hidden', '');

                        // Show image container
                        document.querySelector('#image-container').removeAttribute('hidden');

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imgPreview.src = e.target.result;
                            imgPreview.style.display = 'block';
                        };

                        reader.readAsDataURL(file);
                    } else {
                        e.target.value = ""; // Clear the file input
                        notaHidden.removeAttribute('hidden');
                        errorElement.textContent = 'File harus kurang dari 1 MB.';
                        notaContainer.classList.add('is-invalid');
                    }
                });
            });

            /*
            Handle Update password
            */
            const formsPhoto = document.querySelectorAll('#formPhoto');
            // Loop over them and prevent submission
            Array.from(formsPhoto).forEach(form => {
                form.addEventListener('submit', event => {
                    let fileSizeInMb = 0;
                    const fileMaxSizeInMb = 1;

                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        event.preventDefault()
                        event.stopPropagation()
                        updatePhoto(form);
                    }

                    form.classList.add('was-validated')
                }, false)
            })


            function handleValidationErrorFromServer(errors, container) {
                let alertContainer = $('#' + container);
                let alertMessage = "";

                // jika error dari server
                $.each(errors, function(key, errorArray) {
                    $.each(errorArray, function(index, errorMessage) {
                        alertMessage += `<li class="error">${errorMessage}</li>`;
                    });
                });

                let alert = `
    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert">
        <div id="alertMessage">
            ${alertMessage}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    `

                alertContainer.html(alert);
            }


        })()
    </script>
@endpush
