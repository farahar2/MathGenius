<?php

namespace App\Http\Requests\Niveau;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'   => ['sometimes', 'string', 'max:100', Rule::unique('niveaux', 'nom')->ignore($this->route('niveau'))],
            'ordre' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
