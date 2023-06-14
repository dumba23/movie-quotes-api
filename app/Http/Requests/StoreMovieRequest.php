<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'release_date' => 'required|date',
            'genreIds' => 'required|array',
            'genreIds.*' => 'exists:genres,id',
            'description' => 'nullable|string',
            'director' => 'nullable|string|max:255',
            'image' => 'required',
        ];
    }
}
