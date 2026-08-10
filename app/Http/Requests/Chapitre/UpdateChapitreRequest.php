<?php

namespace App\Http\Requests\Chapitre;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChapitreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'        => ['sometimes', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'ordre'        => ['sometimes', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
            'id_niveau'    => ['sometimes', 'integer', 'exists:niveaux,id'],
        ];
    }
}
