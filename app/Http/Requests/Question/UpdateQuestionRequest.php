<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question'      => ['sometimes', 'string'],
            'option_a'      => ['sometimes', 'string', 'max:500'],
            'option_b'      => ['sometimes', 'string', 'max:500'],
            'option_c'      => ['sometimes', 'string', 'max:500'],
            'option_d'      => ['sometimes', 'string', 'max:500'],
            'bonne_reponse' => ['sometimes', 'in:A,B,C,D'],
            'explication'   => ['nullable', 'string'],
            'notion'        => ['nullable', 'string'],
            'ordre'         => ['sometimes', 'integer', 'min:0'],
            'id_quiz'       => ['sometimes', 'exists:quiz,id'],
        ];
    }
}
