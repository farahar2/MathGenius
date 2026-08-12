<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Les pages `/app/*` n'étaient protégées que côté client (un test du
 * localStorage dans app.js) : le HTML, y compris celui du panneau
 * d'administration, était servi à n'importe quel visiteur.
 */
class AppPageAccessTest extends TestCase
{
    use RefreshDatabase;

    public static function protectedPages(): array
    {
        return [
            'dashboard' => ['/app/dashboard'],
            'chapitres' => ['/app/chapters'],
            'quiz' => ['/app/quiz'],
            'resultats' => ['/app/resultats'],
            'profil' => ['/app/profil'],
            'admin' => ['/app/admin'],
        ];
    }

    #[DataProvider('protectedPages')]
    public function test_guest_is_redirected_to_login(string $url): void
    {
        $this->get($url)->assertRedirect('/login');
    }

    /**
     * Les noms `login` / `register` doivent rester ceux des pages web :
     * s'ils sont repris par les routes API, `route('login')` renvoie vers
     * POST api/login et casse les liens du site comme la redirection du
     * middleware `auth`.
     */
    public function test_named_auth_routes_point_to_the_web_pages(): void
    {
        $this->assertSame(url('/login'), route('login'));
        $this->assertSame(url('/register'), route('register'));
    }

    public function test_student_can_reach_the_regular_pages(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get('/app/dashboard')->assertStatus(200);
        $this->actingAs($student)->get('/app/profil')->assertStatus(200);
    }

    public function test_student_cannot_reach_the_admin_page(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get('/app/admin')->assertStatus(403);
    }

    public function test_formateur_can_reach_the_admin_page(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);

        $this->actingAs($formateur)->get('/app/admin')->assertStatus(200);
    }

    public function test_admin_can_reach_the_admin_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/app/admin')->assertStatus(200);
    }
}
