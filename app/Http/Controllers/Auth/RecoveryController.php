<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\MailRecovery;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoveryController extends Controller
{
	public function recover(RecoverPasswordRequest $request): JsonResponse
	{
		$user = User::where('email', $request->email)->first();

		$token = Str::random(30);

		$user->update([
			'password_reset_token' => $token,
		]);

		Mail::to($user->email)->send(new MailRecovery($token));

		return response()->json(['sent_mail' => true], 200);
	}

	public function updatePassword(UpdatePasswordRequest $request): JsonResponse
	{
		$user = User::where('password_reset_token', $request->token)->first();

		if ($user === null) {
			return response()->json(['updated' => false, 'error_message' => 'User not found'], 404);
		}

		$user->update(['password' => $request->password, 'password_reset_token' => null]);

		return response()->json(['updated' => true], 201);
	}
}
