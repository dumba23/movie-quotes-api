<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class VerifyController extends Controller
{
	public function verify(string $token): JsonResponse
	{
		$user = User::where('email_verify_token', $token)->first();

		if ($user == null) {
			return response()->json(['Invalid' => true, 400]);
		}

		$user->update([
			'email_verified_at'  => Carbon::now(),
			'email_verify_token' => '',
		]);

		return response()->json(['Success' => true], 202);
	}
}
