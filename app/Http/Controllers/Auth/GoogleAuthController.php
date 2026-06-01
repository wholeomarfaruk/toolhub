<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ConnectedAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception) {
            return redirect('/login')->withErrors('Google login failed. Please try again.');
        }

        // Check if user is already authenticated (linking scenario)
        if (Auth::check()) {
            $currentUser = Auth::user();

            // Check if Google ID is already taken by another user
            $existingAccount = ConnectedAccount::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->where('user_id', '!=', $currentUser->id)
                ->first();

            if ($existingAccount) {
                return redirect('/settings/profile')->withErrors('This Google account is already linked to another account.');
            }

            // Link Google account to current user
            ConnectedAccount::updateOrCreate(
                ['user_id' => $currentUser->id, 'provider' => 'google'],
                [
                    'provider_id' => $googleUser->getId(),
                    'provider_email' => $googleUser->getEmail(),
                    'provider_data' => [
                        'name' => $googleUser->getName(),
                        'avatar' => $googleUser->getAvatar(),
                    ],
                ]
            );

            return redirect('/settings/profile')->with('message', 'Google account linked successfully!');
        }

        // Find or create user (login scenario)
        // First check if Google account is connected
        $connectedAccount = ConnectedAccount::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        if ($connectedAccount) {
            $user = $connectedAccount->user;
        } else {
            // Try to find by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(40)), // Random password
                    'email_verified_at' => now(),
                ]);
            }

            // Link Google account
            ConnectedAccount::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'provider_email' => $googleUser->getEmail(),
                'provider_data' => [
                    'name' => $googleUser->getName(),
                    'avatar' => $googleUser->getAvatar(),
                ],
            ]);
        }

        // Log user in
        Auth::login($user, remember: true);

        return redirect('/dashboard');
    }

    /**
     * Disconnect Google account
     */
    public function disconnect()
    {
        if (auth()->check()) {
            auth()->user()->update(['google_id' => null]);
        }

        return redirect('/settings/profile')->with('message', 'Google account disconnected.');
    }
}
