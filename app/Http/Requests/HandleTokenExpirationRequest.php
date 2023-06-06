<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandleTokenExpirationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required|size:30'
        ];
    }
}
