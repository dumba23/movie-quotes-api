<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\MailChange;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
	public function get(): UserResource
	{
		return UserResource::make(Auth::user());
	}

	public function update(UpdateUserRequest $request): JsonResponse
	{
		$user = User::where('email', $request->old_email)->first();

		if (isset($request->email)) {
			if ($request->email === $request->old_email) {
				$user->update([
					'username' => $request->username,
					'email'    => $request->email,
					'password' => $request->password,
				]);
			} else {
				$user->update([
					'username' => $request->username,
					'password' => $request->password,
				]);

				$encryptedEmail = Crypt::encryptString($user->email);
				Mail::to($user->email)->send(new MailChange($user, $encryptedEmail));
			}
		}

		if ($request->file('avatar')) {
			Storage::delete($user->avatar);
			$validatedData = $request->validated();
			unset($validatedData['avatar']);
			$user->update($validatedData + [
				'avatar' => $request->file('avatar')->store('avatars'),
			]);
		} else {
			$user->update($request->validated());
		}

		return response()->json('User updated successfully');
	}

	public function updateEmail(string $oldEmail, string $decryptedEmail): RedirectResponse | JsonResponse
	{
		$user = User::where('email', $oldEmail)->first();

		if ($user === null) {
			return response()->json(['Invalid' => true, 400]);
		}

		$user->update([
			'email' => Crypt::decryptString($decryptedEmail),
		]);

		return redirect(Config::get('app.frontend_url' . '/?verified=true'));
	}
}
