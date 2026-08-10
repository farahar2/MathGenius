<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return User::create([
                'name'       => $data['name'],
                'prenom'     => $data['prenom'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'role'       => $data['role'] ?? 'student',
                'niveau_id' => $data['niveau_id'] ?? null,
            ]);
        });
    }
}
