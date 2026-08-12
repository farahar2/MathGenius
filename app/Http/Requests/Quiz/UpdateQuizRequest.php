<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_lecon'       => ['sometimes', 'exists:lecons,id'],
            'id_chapitre'    => ['nullable', 'exists:chapitres,id'],
            'difficulte'     => ['sometimes', 'in:facile,moyen,difficile'],
            'niveau'         => ['nullable', 'in:debutant,intermediaire,avance'],
            'duree_secondes' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
