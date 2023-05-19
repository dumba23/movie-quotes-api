<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register(RegisterRequest $request)
	{
		$email_verify_token = Str::random(30);

		User::create($request->validated() + [
			'email_verify_token' => $email_verify_token,
		]);

		return response()->json(['success' => true, 'token' => $email_verify_token], 201);
	}
}
