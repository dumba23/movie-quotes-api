<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkSelectedAsReadRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'ids'   => 'required|array',
			'ids.*' => 'exists:notifications,id',
		];
	}
}
