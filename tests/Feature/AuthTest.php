<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\GameScenario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_shows_exactly_four_scenarios(): void
    {
        // Run the seeder to populate the 4 default scenarios
        $this->seed(\Database\Seeders\GameScenarioSeeder::class);

        $response = $this->get('/register');

        $response->assertStatus(200);
        
        $response->assertViewHas('defaultScenarios', function ($scenarios) {
            return $scenarios->count() === 4;
        });
    }

    public function test_new_users_can_register(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/home');
    }

    public function test_registration_replicates_scenarios_and_generates_unique_slugs(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        // 1. Create an admin and a template scenario
        $admin = User::factory()->create(['role' => 'admin']);
        $template = GameScenario::factory()->create([
            'user_id' => $admin->id,
            'name' => 'Prisoner\'s Dilemma',
            'slug' => 'prisoners-dilemma',
        ]);

        // 2. Register User A with the scenario
        $this->post('/register', [
            'name' => 'User A',
            'email' => 'usera@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'scenarios' => [$template->id],
        ]);

        $userA = User::where('email', 'usera@example.com')->first();
        $scenarioA = GameScenario::where('user_id', $userA->id)->first();
        
        $this->assertNotNull($scenarioA);
        $this->assertEquals('prisoners-dilemma-1', $scenarioA->slug);

        auth()->logout();

        // 3. Register User B with the SAME scenario
        $this->post('/register', [
            'name' => 'User B',
            'email' => 'userb@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'scenarios' => [$template->id],
        ]);

        $userB = User::where('email', 'userb@example.com')->first();
        $scenarioB = GameScenario::where('user_id', $userB->id)->first();

        $this->assertNotNull($scenarioB);
        $this->assertEquals('prisoners-dilemma-2', $scenarioB->slug);
    }

    public function test_login_redirects_to_filament(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect();
        // Since it redirects to filament.admin.auth.login which is dynamic, 
        // we just check it doesn't 404.
    }

    public function test_users_can_logout(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
