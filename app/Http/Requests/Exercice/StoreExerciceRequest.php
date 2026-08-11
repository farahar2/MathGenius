<?php

namespace App\Http\Requests\Exercice;

use Illuminate\Foundation\Http\FormRequest;

class StoreExerciceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'        => ['required', 'string'],
            'enonce'       => ['required', 'string'],
            'correction'   => ['required', 'string'],
            'image'        => ['nullable', 'string'],
            'fichier_pdf'  => ['nullable', 'string'],
            'ordre'        => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'id_lecon'     => ['required', 'exists:lecons,id'],
        ];
    }
}
