<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'><g transform='translate(5, 10) scale(1.4)'><path d='M18 2L2 9v18l16 7 16-7V9L18 2z' fill='%23435ebe'/><path d='M18 2v15.5l16-7M18 17.5L2 9.5M18 34V17.5' stroke='%23fff' stroke-width='1.5' fill='none'/><path d='M18 7.5L6 12.7v5.3l12-5.2 12 5.2v-5.3L18 7.5z' fill='%2341bbdd'/></g></svg>">
    
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
            font-weight: 500;
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
            /* background-color: #6366F1; */
            background-color: #435ebe;
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
            background: linear-gradient(135deg, #6366F1 0%, #435ebe 100%);
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
</body>
</html>