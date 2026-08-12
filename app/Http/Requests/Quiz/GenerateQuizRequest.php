<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class GenerateQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_lecon'         => ['required', 'integer', 'exists:lecons,id'],
            'difficulte'       => ['nullable', 'in:facile,moyen,difficile'],
            'niveau'           => ['nullable', 'in:debutant,intermediaire,avance'],
            'nombre_questions' => ['nullable', 'integer', 'min:1', 'max:20'],
            'duree_secondes'   => ['nullable', 'integer', 'min:0'],
        ];
    }
}
