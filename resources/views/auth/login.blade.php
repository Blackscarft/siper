@extends('layouts.auth-v2')

@section('content')
<div class="main-wrapper">
    <div class="login-section">
        <div class="mb-5 text-center">
            <h2 class="login-title">LOGIN</h2>
            <p class="login-subtitle">Sistem Informasi Gudang</p>
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
                <button type="submit" class="btn btn-login">Login Now</button>
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