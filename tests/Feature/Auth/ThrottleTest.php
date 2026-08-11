<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_throttled_after_too_many_failed_attempts(): void
    {
        User::factory()->create([
            'email' => 'jean@example.com',
            'password' => bcrypt('password123'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'jean@example.com',
                'password' => 'wrong-password',
            ])->assertStatus(422);
        }

        $this->postJson('/api/login', [
            'email' => 'jean@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }

    public function test_login_attempts_are_not_shared_across_different_emails(): void
    {
        User::factory()->create([
            'email' => 'jean@example.com',
            'password' => bcrypt('password123'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'jean@example.com',
                'password' => 'wrong-password',
            ])->assertStatus(422);
        }

        $this->postJson('/api/login', [
            'email' => 'autre@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(422);
    }

    public function test_register_is_throttled_after_too_many_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/register', [
                'email' => 'jean@example.com',
            ])->assertStatus(422);
        }

        $this->postJson('/api/register', [
            'email' => 'jean@example.com',
        ])->assertStatus(429);
    }
}
