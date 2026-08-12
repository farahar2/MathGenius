<?php

namespace App\Http\Requests\Recommandation;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecommandationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message'     => ['nullable', 'string'],
            'id_chapitre' => ['required', 'exists:chapitres,id'],
        ];
    }
}
