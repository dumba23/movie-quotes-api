<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class VerifyController extends Controller
{
	public function verify(string $token): JsonResponse | RedirectResponse
	{
		$user = User::where('email_verify_token', $token)->first();

		if ($user == null) {
			return response()->json(['Invalid' => true, 400]);
		}

		$user->update([
			'email_verified_at'  => Carbon::now(),
			'email_verify_token' => '',
		]);

        return redirect()->away(Config::get('app.frontend_url') . '/?verified=true');
	}
}
