<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\MailConfirm;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$email_verify_token = Str::random(30);

		$user = User::create($request->validated() + [
			'avatar'             => config('app.url') . '/images/' . basename(public_path('images/avatar.png')),
			'email_verify_token' => $email_verify_token,
		]);

		Mail::to($user->email)->send(new MailConfirm($user));

		return response()->json(['success' => true, 'token' => $email_verify_token], 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		$remember = $request->remember_token;

		$request->merge([$login_type => $request->input('login')]);

		$user = User::where($login_type, $request->$login_type)->first();

		if ($user && $user->email_verified_at == null) {
			return response()->json([
				'message' => __('validation.email_not_confirmed'),
			], 401);
		}

		if (!Auth::attempt($request->only($login_type, 'password'), $remember)) {
			return response()->json([
				'status'  => false,
				'message' => [
					'en' => 'Provided credentials does not match with our records',
					'ka' => 'თქვენს მიერ მოწოდებული მონაცემები არ არის სწორი',
				],
				'locale' => app()->getLocale(),
			], 401);
		}
		request()->session()->regenerate();

		return response()->json([
			'status'  => true,
			'message' => 'User Logged In Successfully',
		], 200);
	}

	public function logout(): JsonResponse
	{
		Auth::guard('web')->logout();

		return response()->json([
			'status'  => true,
			'message' => 'User Logged Out Successfully',
		], 200);
	}
}
