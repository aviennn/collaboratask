<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
<div class="absolute top-0 left-0 p-4 text-white z-10 flex items-center w-full">
    <img src="{{ asset('dist/img/puzzle.png') }}" alt="CollaboraTask Logo" class="w-15 h-8 sm:w-50 sm:h-10" />
</div>

<div class="min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-start bg-white">
    <div class="flex flex-col justify-center items-center w-full md:w-1/2 p-8">
        <div class="w-full max-w-md space-y-6">
            @yield('content')
        </div>
    </div>

    <!-- Background Image Section -->
    <div class="hidden md:block w-full h-screen bg-cover bg-center" style="background-image: url('{{ asset('dist/img/newbg.png') }}');"></div>
</div>

<style>
    .bg-blue-900 { background-color: #00355b; }
    .text-white { color: black; }
    .min-h-screen { min-height: 100vh; }
</style>
</body>
</html>
