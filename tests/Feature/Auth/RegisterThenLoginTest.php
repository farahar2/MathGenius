<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Aucun test ne couvrait l'enchaînement inscription -> connexion : les
 * tests d'auth existants créent l'utilisateur avec bcrypt() en direct,
 * ce qui court-circuite RegisterUserAction et le cast `hashed`.
 */
class RegisterThenLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_freshly_registered_user_can_log_in(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Aqaa',
            'prenom' => 'Samia',
            'email' => 'samia.aqaa@example.com',
            'password' => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
        ])->assertStatus(201);

        $this->postJson('/api/login', [
            'email' => 'samia.aqaa@example.com',
            'password' => 'motdepasse123',
        ])->assertStatus(200)->assertJsonStructure(['user', 'token']);
    }

    public function test_registration_stores_a_single_bcrypt_hash(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Aqaa',
            'prenom' => 'Samia',
            'email' => 'hash@example.com',
            'password' => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
        ])->assertStatus(201);

        $user = User::where('email', 'hash@example.com')->firstOrFail();

        // Un double hachage produirait un hash valide mais que le mot de
        // passe d'origine ne vérifie pas.
        $this->assertTrue(Hash::check('motdepasse123', $user->password));
    }
}
