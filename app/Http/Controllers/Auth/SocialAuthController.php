<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'uuid' => (string) Str::uuid(),
                'first_name' => $googleUser->user['given_name'] ?? ($googleUser->getName() ?: 'User'),
                'last_name' => $googleUser->user['family_name'] ?? '',
                'password' => Hash::make(Str::random(32)),
                'status' => 'active',
            ]
        );

        // Optionally attach a default role if none exists
        if (method_exists($user, 'roles') && $user->roles()->count() === 0) {
            try { $user->roles()->attach(\App\Models\Role::where('name','student')->value('id')); } catch (\Throwable $e) {}
        }

        $token = $user->createToken('api')->plainTextToken;

        // Redirect back to SPA with token so the client can store it
        return redirect()->away(url('/student/login?token=' . urlencode($token)));
    }
}
