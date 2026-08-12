<?php

namespace App\Http\Requests\Niveau;

use Illuminate\Foundation\Http\FormRequest;

class StoreNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'   => ['required', 'string', 'max:100', 'unique:niveaux,nom'],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
