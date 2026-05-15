<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory System - Login</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="icon" type="image/png" href="{{ asset('auth/images/icons/favicon.ico') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/vendor/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5; 
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-wrapper {
            width: 90%;
            max-width: 900px;
            height: 550px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Form Section */
        .login-section {
            flex: 1.2;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-weight: 800; /* Membuat font sangat tebal */
            font-size: 1.75rem;
            letter-spacing: 1px; /* Memberi jarak antar huruf agar elegan */
            color: #1F2937;
            margin-bottom: 8px;
            text-transform: uppercase; /* Opsional: Membuat semua huruf kapital */
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: #6B7280;
            font-weight: 400;
        }

        /* Input Styling */
        .input-group {
            background-color: #F3F4F6;
            border-radius: 12px;
            border: 1px solid transparent;
            transition: 0.3s;
            margin-bottom: 20px;
            /* Pastikan group memiliki tinggi yang konsisten */
            min-height: 55px; 
        }

        .input-group:focus-within {
            border-color: #6366F1;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .input-group-text {
            background: transparent !important;
            border: none !important;
            padding-left: 18px;
            padding-right: 10px;
            color: #9CA3AF;
            /* Tambahan agar rata tengah sempurna */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            background: transparent !important;
            border: none !important;
            padding: 12px 15px 12px 5px;
            font-size: 0.95rem;
            height: auto;
            align-self: center;
        }

        .form-control:focus {
            box-shadow: none !important;
        }

        /* Button */
        .btn-login {
            background-color: #6366F1;
            color: white;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            border: none;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        /* Visual Section */
        .visual-section {
            flex: 1;
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .visual-image {
            max-width: 85%;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .visual-section { display: none; }
            .main-wrapper { max-width: 400px; height: auto; }
        }
    </style>
</head>
<body>
    @yield('content')
    <script src="{{ asset('auth/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('auth/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>