<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Mail\MailChange;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
	public function getUser(): JsonResponse
	{
		return response()->json(Auth::user());
	}

	public function editUser(EditUserRequest $request): JsonResponse
	{
		$user = User::where('email', $request->email)->first();

		if (isset($request->avatar)) {
			Storage::delete($user->avatar);
		}

		if ($request->email === $request->old_email) {
			$user->update([
				$user->username = $request->validated('username'),
				$user->email = $request->validated('email'),
				$user->password = $request->validated('password'),
			]);
		} else {
			Mail::to($user->email)->send(new MailChange($user, Crypt::decryptString($user->email)));

			$user->update([
				$user->username => $request->validated('username'),
				$user->password => $request->validated('password'),
			]);
		}
		return response()->json([$request->validated(), 'User updated successfully', $user->username]);
	}

	public function changeEmail(string $oldEmail, string $decryptedEmail): RedirectResponse | JsonResponse
	{
		$user = User::where('email', $oldEmail)->first();

		if ($user === null) {
			return response()->json(['Invalid' => true, 400]);
		}

		$user->update([
			'email' => Crypt::encryptString($decryptedEmail),
		]);

		return redirect(Config::get('app.frontend_url' . '/?verified=true'));
	}
}
