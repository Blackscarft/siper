@extends('layouts.auth-v2')

@section('content')
<div class="main-wrapper">
    <div class="login-section">
        <div class="mb-5 text-center">
            <img style="height: 4.5rem" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxODAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCAyMDAgNjAiPgogIDxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDUsIDEwKSBzY2FsZSgxLjQpIj4KICAgIDxwYXRoIGQ9Ik0xOCAyTDIgOXYxOGwxNiA3IDE2LTdWOUwxOCAyWiIgZmlsbD0iIzQzNWViZSIvPgogICAgPHBhdGggZD0iTTE4IDJ2MTUuNWwxNi03TTE4IDE3LjVMMiA5LjVNMTggMzRWMTcuNSIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjEuNSIgZmlsbD0ibm9uZSIvPgogICAgPHBhdGggZD0iTTE4IDcuNUw2IDEyLjd2NS4zbDEyLTUuMiAxMiA1LjJ2LTUuM0wxOCA3LjVaIiBmaWxsPSIjNDFiYmRkIi8+CiAgPC9nPgogIDx0ZXh0IHg9IjYwIiB5PSI0OCIgZm9udC1mYW1pbHk9InNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iNDIiIGZvbnQtd2VpZ2h0PSI5MDAiIGZpbGw9IiM0MzVlYmUiPlNJUEVSPC90ZXh0Pgo8L3N2Zz4=" alt="Logo SIPER">
            <p class="login-subtitle">Sistem Informasi Persediaan</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 small py-2">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user-o"></i></span>
                <input type="email" name="email" class="form-control" 
                        placeholder="Username" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" 
                        placeholder="Password" required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-login btn-block" style="cursor: pointer;">Login</button>
            </div>
        </form>
    </div>

    <div class="visual-section">
        <div style="position:absolute; top:10%; right:10%; width:100px; height:100px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
        
        <img src="https://static.vecteezy.com/system/resources/thumbnails/049/772/512/small/warehouse-worker-organizing-inventory-with-technology-transparent-background-image-illustration-png.png" 
            alt="Login Illustration" class="visual-image">

        <div style="position:absolute; bottom:5%; left:10%; width:60px; height:60px; background:rgba(255,255,255,0.15); border-radius:50%;"></div>
    </div>
</div>
@endsection