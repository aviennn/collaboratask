<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
<div class="absolute top-0 left-0 p-4 text-white z-10 flex items-center w-full">
    <img src="{{ asset('dist/img/puzzle.png') }}" alt="CollaboraTask Logo" class="w-50 h-10" />
</div>

<!-- Centered container on mobile screens -->
<div class="min-h-screen flex flex-col justify-center items-center md:flex-row">
    <!-- Left side: Registration Form -->
    <div class="flex flex-col justify-center items-center w-full md:w-1/2 p-18 md:p-15 bg-white" style="padding-bottom: 6rem;">

        <div class="w-full max-w-md space-y-6">
            @yield('content')
        </div>
    </div>

    <!-- Right side: Background image -->
    <div class="hidden md:block w-full h-screen bg-cover bg-center" style="background-image: url('{{ asset('dist/img/newbg.png') }}');"></div>
</div>

<style>
    .bg-blue-900 { background-color: #00355b; }
    .text-white { color: black; }
    .min-h-screen { min-height: 100vh; }

    .p-15 {
    padding: 2.7rem;
}
.p-16 {
    padding: 2.7rem;
}
.custom-margin-top {
    margin-top: 20px; /* Adjust as needed */
}
.custom-margin-buttom {
    margin-top: 20px; /* Adjust as needed */
}
.custom-margin-top-h2 {
    margin-top: 20px; /* Customize the margin as needed */
}
.custom-margin-top-p {
    margin-top: 15px; /* Customize the margin as needed */
}
.p-17 {
    padding-bottom: 2.7rem;
}
.p-18 {
    padding: 2.5rem;
}

@media (max-width: 768px) {
    h2 {
        font-size: 28px; /* Smaller font size for mobile */
    }
    p {
        font-size: 18px; /* Smaller font size for mobile */
    }
}


</style>
</body>
</html>
