<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$this->merge([
			'title' => [
				'en' => $this->title_en,
				'ka' => $this->title_ka,
			],
			'description'=> [
				'en' => $this->description_en,
				'ka' => $this->description_ka,
			],
			'director' => [
				'en' => $this->director_en,
				'ka' => $this->director_ka,
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
			'title'          => 'required',
			'title.en'       => 'required|string|max:255',
			'title.ka'       => 'required|string|max:255',
			'release_date'   => 'required|string',
			'genreIds'       => 'required|array',
			'genreIds.*'     => 'exists:genres,id',
			'description'    => 'required',
			'description.en' => 'required|string',
			'description.ka' => 'required|string',
			'director'       => 'required',
			'director.en'    => 'required|string|max:255',
			'director.ka'    => 'required|string|max:255',
			'image'          => 'sometimes|image',
		];
	}
}
