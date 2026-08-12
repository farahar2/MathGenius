<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'prenom'     => ['required', 'string', 'max:100'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8'],
            'role'       => ['sometimes', 'string', 'in:student,formateur,admin'],
            'is_premium' => ['sometimes', 'boolean'],
            'niveau_id'  => ['sometimes', 'nullable', 'integer', 'exists:niveaux,id'],
        ];
    }
}
