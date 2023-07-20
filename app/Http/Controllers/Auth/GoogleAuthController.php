<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirectToGoogle(): RedirectResponse
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function handleProviderCallback(): RedirectResponse | JsonResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		$matchedUser = User::where('email', $googleUser->email)->first();

		if (!$matchedUser) {
			$createdUser = User::create([
				'username'              => $googleUser->name,
				'google_id'             => $googleUser->id,
				'email'                 => $googleUser->email,
				'avatar'                => config('app.url') . '/images/' . basename(public_path('images/avatar.png')),
				'password'              => Str::random(7),
				'email_verify_token'    => null,
				'email_verified_at'     => Carbon::now(),
			]);

			Auth::login($createdUser);
			request()->session()->regenerate();

			return redirect(Config::get('app.frontend_url') . '/news-feed');
		}

		Auth::login($matchedUser);
		request()->session()->regenerate();

		return redirect(Config::get('app.frontend_url') . '/news-feed');
	}
}
