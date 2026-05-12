<!DOCTYPE html>
<html lang="en">

@include('components.header')

{{-- sweetalert2 --}}
<link rel="stylesheet" href="{{ asset('extensions/sweetalert2/sweetalert2.min.css') }}">

{{-- Data Table --}}
<link rel="stylesheet" href="{{ asset('extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-datatable-jquery.css') }}">
<style>
    .center-cropped {
        width: 200px;
        /* Set width */
        height: 200px;
        /* Set height (same as width for a square crop) */
        object-fit: cover;
        /* Ensures the image covers the container */
        object-position: center;
        /* Center the image inside the container */
    }
</style>
@stack('style')

<body>
    <script src="{{ asset('static/js/initTheme.js') }}"></script>
    <div id="app">
        @include('components.sidebar')
        <div id="main" class="layout-navbar navbar-fixed">
            @include('components.navbar')
            <div id="main-content">
                @yield('main')
                @include('components.footer')
            </div>
        </div>
    </div>
    <script src="{{ asset('static/js/components/dark.js') }}"></script>
    <script src="{{ asset('extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>


    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Library Jquery --}}
    <script src="{{ asset('extensions/jquery/jquery.min.js') }}"></script>
    {{-- Sweetalert2 --}}
    <script src="{{ asset('extensions/sweetalert2/sweetalert2.min.js') }}"></script>

    {{-- Validation --}}
    <script src="{{ asset('extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('static/js/pages/parsley.js') }}"></script>

    {{-- Costom Alert --}}
    <script>
        const Swal2 = Swal.mixin({
            customClass: {
                input: 'form-control'
            }
        })

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    </script>

    @stack('script')

</body>

</html>
