<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_ka' => 'required|string|max:255',
            'release_date' => 'required|numeric',
            'description_en' => 'required|string',
            'description_ka' => 'required|string',
            'director_en' => 'required|string|max:255',
            'director_ka' => 'required|string|max:255',
            'genreIds' => 'required|array',
            'genreIds.*' => 'exists:genres,id',
            'image' => 'sometimes|image',
        ];
    }
}