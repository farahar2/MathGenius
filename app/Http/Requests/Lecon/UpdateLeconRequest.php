<?php

namespace App\Http\Requests\Lecon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeconRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'        => ['sometimes', 'string', 'max:255'],
            'contenu'      => ['sometimes', 'string'],
            'image'        => ['nullable', 'string', 'max:255'],
            'fichier_pdf'  => ['nullable', 'string', 'max:255'],
            'ordre'        => ['sometimes', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
            'id_chapitre'  => ['sometimes', 'integer', 'exists:chapitres,id'],
        ];
    }
}
