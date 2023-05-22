<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\MailConfirm;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$email_verify_token = Str::random(30);

		$user = User::create($request->validated() + [
			'email_verify_token' => $email_verify_token,
		]);

		Mail::to($user->email)->send(new MailConfirm($user));

		return response()->json(['success' => true, 'token' => $email_verify_token], 201);
	}
}
