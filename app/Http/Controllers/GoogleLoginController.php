<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();
    $user = User::where('email', $googleUser->email)->first();

    if (!$user) {
        // If the user doesn't exist, create one but don't mark the email as verified
        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => bcrypt(Str::random(16)), // Generate a random password
        ]);

        // Send email verification notification
        event(new Registered($user));
    }

    // Check if the user has already verified their email
    if (is_null($user->email_verified_at)) {
        // Send email verification notification if not verified
        $user->sendEmailVerificationNotification();
    }

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}
    
}
