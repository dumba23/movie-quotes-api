<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$this->merge([
			'title' => [
				'en' => $this->title_en,
				'ka' => $this->title_ka,
			],
		]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'title'    => 'required',
			'title.en' => 'required|string|max:255',
			'title.ka' => 'required|string|max:255',
			'image'    => 'sometimes|image',
		];
	}
}
