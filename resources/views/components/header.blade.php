<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <title>@yield('title') &mdash; {{ config('app.name', 'Laravel') }}</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'><g transform='translate(5, 10) scale(1.4)'><path d='M18 2L2 9v18l16 7 16-7V9L18 2z' fill='%23435ebe'/><path d='M18 2v15.5l16-7M18 17.5L2 9.5M18 34V17.5' stroke='%23fff' stroke-width='1.5' fill='none'/><path d='M18 7.5L6 12.7v5.3l12-5.2 12 5.2v-5.3L18 7.5z' fill='%2341bbdd'/></g></svg>">
    <link rel="stylesheet" href="{{ asset('extensions/@fortawesome/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" crossorigin href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('css/iconly.css') }}">
</head>
