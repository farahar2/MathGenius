<?php

namespace App\Http\Requests\Lecon;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeconRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'        => ['required', 'string', 'max:255'],
            'contenu'      => ['required', 'string'],
            'image'        => ['nullable', 'string', 'max:255'],
            'fichier_pdf'  => ['nullable', 'string', 'max:255'],
            'ordre'        => ['sometimes', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
            'id_chapitre'  => ['required', 'integer', 'exists:chapitres,id'],
        ];
    }
}
