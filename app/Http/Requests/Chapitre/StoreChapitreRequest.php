<?php

namespace App\Http\Requests\Chapitre;

use Illuminate\Foundation\Http\FormRequest;

class StoreChapitreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ordre'       => ['sometimes', 'integer', 'min:0'],
            'is_published'=> ['sometimes', 'boolean'],
            'id_filiere'  => ['required', 'integer', 'exists:filieres,id'],
        ];
    }
}
