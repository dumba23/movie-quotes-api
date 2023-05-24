<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirectToGoogle()
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function handleProviderCallback(): JsonResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		$matchedUser = User::where('email', $googleUser->email)->first();

		if (!$matchedUser) {
			$createdUser = User::create([
				'username'              => $googleUser->name,
				'email'                 => $googleUser->email,
				'password'              => Str::random(7),
				'email_verify_token'    => null,
				'email_verified_at'     => Carbon::now(),
			]);
			Auth::login($createdUser);
			return response()->json(['logged_in' => true], 200);
		}
		Auth::login($matchedUser);
		return response()->json(['logged_in' => true], 200);
	}
}
