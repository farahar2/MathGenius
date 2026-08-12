<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['sometimes', 'string', 'max:255'],
            'prenom'     => ['sometimes', 'string', 'max:100'],
            'email'      => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password'   => ['sometimes', 'nullable', 'string', 'min:8'],
            'role'       => ['sometimes', 'string', 'in:student,formateur,admin'],
            'is_premium' => ['sometimes', 'boolean'],
            'niveau_id'  => ['sometimes', 'nullable', 'integer', 'exists:niveaux,id'],
        ];
    }
}
