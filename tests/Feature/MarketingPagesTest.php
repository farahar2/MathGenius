<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les pages publiques doivent rendre sans erreur. Le layout marketing
 * dupliquait les tokens CSS du partial head-assets ; ces tests couvrent
 * son rendu après factorisation.
 */
class MarketingPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('MathGenius', false);
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_register_page_renders(): void
    {
        $this->get('/register')->assertStatus(200);
    }
}
