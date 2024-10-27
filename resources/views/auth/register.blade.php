@extends('layouts.register-layout')

@section('content')
<h2 class="text-left font-semibold text-xl mb-8 custom-margin-top-h2" style="font-size: 33px;">{{ __('CREATE AN ACCOUNT') }}</h2>
<p class="text-left mb-6 custom-margin-top-p" style="font-size: 20px;">Please enter your credentials to create an account.</p>
 <form method="POST" action="{{ route('register') }}" style="margin-top: -2px;">
        @csrf
<br><br>
        <!-- Name -->
        <div class="mb-9">
            <x-input-label for="name" :value="__('Name')" style="font-size: 20px; color: #000; margin-bottom: 8px;"/>
            <x-text-input id="name" class="custom-input block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />

        </div>

        <!-- Email Address -->
        <div class="mb-9">
            <x-input-label for="email" :value="__('Email')" style="font-size: 20px; color: #000; margin-bottom: 8px;" />
            <x-text-input id="email" class="custom-input block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-9">
            <x-input-label for="password" :value="__('Password')" style="font-size: 20px; color: #000; margin-bottom: 8px;"/>
            <x-text-input id="password" class="custom-input block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-9">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" style="font-size: 20px; color: #000; margin-bottom: 8px;"/>
            <x-text-input id="password_confirmation" class="custom-input block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="ml-4" style="font-size: 19px; color: #000;">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
@endsection
