<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'username'              => 'sometimes|unique:users,username,' . $this->user()->id,
			'old_email'             => 'sometimes|exists:users,email',
			'email'                 => 'sometimes|email|unique:users,email,' . $this->user()->id,
			'avatar'                => 'sometimes|image',
			'password'              => 'sometimes',
			'password_confirmation' => 'sometimes|same:password',
		];
	}
}
