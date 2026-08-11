<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question'      => ['required', 'string'],
            'option_a'      => ['required', 'string', 'max:500'],
            'option_b'      => ['required', 'string', 'max:500'],
            'option_c'      => ['required', 'string', 'max:500'],
            'option_d'      => ['required', 'string', 'max:500'],
            'bonne_reponse' => ['required', 'in:A,B,C,D'],
            'explication'   => ['nullable', 'string'],
            'notion'        => ['nullable', 'string'],
            'ordre'         => ['nullable', 'integer', 'min:0'],
            'id_quiz'       => ['required', 'exists:quiz,id'],
        ];
    }
}
