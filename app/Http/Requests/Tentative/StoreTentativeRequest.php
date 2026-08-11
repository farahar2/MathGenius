<?php

namespace App\Http\Requests\Tentative;

use Illuminate\Foundation\Http\FormRequest;

class StoreTentativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_quiz'                   => ['required', 'exists:quiz,id'],
            'reponses'                  => ['required', 'array'],
            'reponses.*.id_question'    => ['required', 'exists:questions,id'],
            'reponses.*.reponse_eleve'  => ['required', 'in:A,B,C,D'],
        ];
    }
}
