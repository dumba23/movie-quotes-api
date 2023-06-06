<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'password'              => 'required|confirmed|min:8|max:15|regex:/^[a-z0-9]+$/',
			'password_confirmation' => 'required|same:password',
		];
	}
}
