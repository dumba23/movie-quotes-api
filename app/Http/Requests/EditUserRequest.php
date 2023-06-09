<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'username'  => 'required|unique:users,username,' . $this->user()->id,
            'old_email' => 'required|exists:users,email',
			'email'     => 'required|email|unique:users,email,' . $this->user()->id,
			'password'  => 'required',
		];
	}
}
