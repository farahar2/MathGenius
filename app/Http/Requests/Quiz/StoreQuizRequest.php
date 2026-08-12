<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_lecon'       => ['required', 'exists:lecons,id'],
            'id_chapitre'    => ['nullable', 'exists:chapitres,id'],
            'difficulte'     => ['nullable', 'in:facile,moyen,difficile'],
            'niveau'         => ['nullable', 'in:debutant,intermediaire,avance'],
            'duree_secondes' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
