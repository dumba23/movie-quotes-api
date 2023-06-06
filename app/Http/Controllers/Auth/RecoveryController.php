<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\HandleTokenExpirationRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\MailRecovery;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoveryController extends Controller
{
	public function recover(RecoverPasswordRequest $request): JsonResponse
	{
		$user = User::where('email', $request->email)->first();

		$token = Str::random(30);

		DB::table('password_reset_tokens')->updateOrInsert(['email' => $user->email], ['token' => $token, 'created_at' => Carbon::now()]);

		Mail::to($user->email)->send(new MailRecovery($token, $user));

		return response()->json(['sent_mail' => true], 200);
	}

	public function updatePassword(UpdatePasswordRequest $request): JsonResponse
	{
		$token = DB::table('password_reset_tokens')->where('token', $request->token)->first();
		$user = User::where('email', $token->email)->first();

		if ($user === null) {
			return response()->json(['updated' => false, 'error_message' => 'User not found'], 404);
		}

		$user->update(['password' => $request->password]);
		DB::table('password_reset_tokens')->where('token', $request->token)->delete();

		return response()->json(['updated' => true], 201);
	}

	public function handleTokenExpiration(HandleTokenExpirationRequest $request): JsonResponse
	{
		$token = DB::table('password_reset_tokens')->where('token', $request->token)->get();
		if (!$token->where('created_at', '>', Carbon::now()->subHours())) {
			DB::table('password_reset_tokens')->where('token', $request->token)->delete();

			return response()->json(['expired' => true], 410);
		}

		return response()->json(['expired' => false], 200);
	}
}
