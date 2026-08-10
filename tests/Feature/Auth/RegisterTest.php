<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'Dupont',
            'prenom'                => 'Jean',
            'email'                 => 'jean@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user'  => ['id', 'name', 'prenom', 'email', 'role', 'is_premium'],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jean@example.com',
            'name'  => 'Dupont',
            'role'  => 'student',
        ]);
    }

    public function test_registration_requires_validation(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'prenom', 'email', 'password']);
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'jean@example.com']);

        $response = $this->postJson('/api/register', [
            'name'                  => 'Dupont',
            'prenom'                => 'Jean',
            'email'                 => 'jean@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_password_must_be_confirmed(): void
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'Dupont',
            'prenom'                => 'Jean',
            'email'                 => 'jean@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
