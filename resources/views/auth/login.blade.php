@extends('layouts.login-layout')

@section('content')
<h2 class="text-left font-semibold mb-4 text-2xl sm:text-3xl">Welcome Back!</h2>
<h2 class="text-left font-semibold mb-4 text-lg sm:text-2xl">Login to Your Account</h2>

<form method="POST" action="{{ route('login') }}">
    @csrf
    <!-- Email Address -->
    <div>
        <h3 class="text-sm sm:text-base">Email</h3>
        <x-text-input id="email" class="custom-input block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <h3 class="text-sm sm:text-base">Password</h3>
        <x-text-input id="password" class="custom-input block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="block mt-4 flex items-center">
        <input type="checkbox" id="remember_me" class="hidden toggle-input" name="remember" />
        <label for="remember_me" class="flex items-center cursor-pointer">
            <div class="relative">
                <span class="block w-11 h-4 bg-gray-300 rounded-full shadow-inner"></span>
                <span class="dot absolute w-4 h-4 bg-white rounded-full shadow -left-1 -top-1 transition"></span>
            </div>
            <h2 class="text-sm ml-2">Remember me</h2>
        </label>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between mt-4 text-sm">
        @if (Route::has('password.request'))
            <a class="underline text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif

        <x-primary-button class="ml-3">{{ __('Log in') }}</x-primary-button>
    </div>
</form>

<!-- Google Login Button -->
<div class="flex items-center justify-center mt-4">
    <a href="{{ route('google.redirect') }}" class="google-btn">
        <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Sign in with Google">
    </a>
</div>

<!-- Register Link -->
<div class="flex items-center justify-center mt-4">
    <span class="text-sm text-gray-600">Not registered?</span>
    <a href="{{ route('register') }}" class="text-sm text-custom-color underline ml-1">{{ __('Create an account') }}</a>
</div>

<style>
    .text-custom-color { color: #5195c6; }
    .text-custom-color:hover { color: #8aceff; }
    .toggle-input:checked + label .block { background-color: #4CAF50; }
    .toggle-input:checked + label .dot { transform: translateX(100%); background-color: #ffffff; }
    .dot { top: 0; left: 0; transition: all 0.3s ease-in-out; }
    .w-11 { width: 2rem; }
    .google-btn img { width: auto; height: 48px; }
</style>
@endsection
