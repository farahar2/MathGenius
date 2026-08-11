<?php

namespace App\Http\Requests\Recommandation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecommandationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['sometimes', 'string'],
            'is_lue'  => ['sometimes', 'boolean'],
        ];
    }
}
