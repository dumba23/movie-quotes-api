<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirectToGoogle()
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function handleProviderCallback(): RedirectResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		$matchedUser = User::where('email', $googleUser->email)->first();

		if (!$matchedUser) {
			$usernameTaken = User::where('username', $googleUser->name)->first();

			if ($usernameTaken) {
				$createdUser = User::create([
					'username'              => $googleUser->name . $googleUser->id,
					'google_id'             => $googleUser->id,
					'email'                 => $googleUser->email,
                    'avatar'                => env('APP_URL') . '/images/' . basename(public_path('/images/avatar.png')),
					'password'              => Str::random(7),
					'email_verify_token'    => null,
					'email_verified_at'     => Carbon::now(),
				]);
			} else {
				$createdUser = User::create([
					'username'              => $googleUser->name,
					'google_id'             => $googleUser->id,
					'email'                 => $googleUser->email,
                    'avatar'                => env('APP_URL') . '/images/' . basename(public_path('images/avatar.png')),
					'password'              => Str::random(7),
					'email_verify_token'    => null,
					'email_verified_at'     => Carbon::now(),
				]);
			}
            Auth::login($createdUser);
            request()->session()->regenerate();

            return redirect(Config::get('app.frontend_url') . '/profile');
		}

		Auth::login($matchedUser);
        request()->session()->regenerate();

        return redirect(Config::get('app.frontend_url') . '/profile');
	}
}
