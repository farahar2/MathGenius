<?php

namespace App\Http\Requests\Exercice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'        => ['sometimes', 'string'],
            'enonce'       => ['sometimes', 'string'],
            'correction'   => ['sometimes', 'string'],
            'image'        => ['nullable', 'string'],
            'fichier_pdf'  => ['nullable', 'string'],
            'ordre'        => ['sometimes', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
            'id_lecon'     => ['sometimes', 'exists:lecons,id'],
        ];
    }
}
