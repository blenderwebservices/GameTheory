<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\GameScenario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameScenarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_simulation_index_page_shows_user_scenarios(): void
    {
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create([
            'user_id' => $user->id,
            'name' => ['en' => 'My Test Scenario', 'es' => 'Mi Escenario de Prueba']
        ]);
        
        $response = $this->actingAs($user)->get('/simulations');

        $response->assertStatus(200);
        $response->assertSee('Escenario'); 
    }

    public function test_user_can_view_simulation(): void
    {
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/simulation/{$scenario->slug}");

        $response->assertStatus(200);
    }

    public function test_user_can_update_simulation_payoffs(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create(['user_id' => $user->id]);

        $newMatrix = [
            'AA' => [10, 10],
            'AB' => [0, 20],
            'BA' => [20, 0],
            'BB' => [5, 5],
        ];

        $response = $this->actingAs($user)->post("/simulation/{$scenario->slug}/save", [
            'payoff_matrix' => $newMatrix,
            'player_a_name' => 'Custom A',
            'player_b_name' => 'Custom B',
            'player_a_strategy_1' => 'S1',
            'player_a_strategy_2' => 'S2',
            'player_b_strategy_1' => 'S1',
            'player_b_strategy_2' => 'S2',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertEquals($newMatrix, $scenario->fresh()->payoff_matrix);
    }

    public function test_user_can_reset_simulation(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        $user = User::factory()->create();
        $defaultMatrix = ['AA' => [1, 1], 'AB' => [0, 0], 'BA' => [0, 0], 'BB' => [1, 1]];
        
        $scenario = GameScenario::factory()->create([
            'user_id' => $user->id,
            'default_payoff_matrix' => $defaultMatrix,
            'payoff_matrix' => ['AA' => [99, 99], 'AB' => [0, 0], 'BA' => [0, 0], 'BB' => [1, 1]],
        ]);

        $response = $this->actingAs($user)->post("/simulation/{$scenario->slug}/reset");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertEquals($defaultMatrix, $scenario->fresh()->payoff_matrix);
    }

    public function test_users_cannot_access_others_simulations(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $scenario1 = GameScenario::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get("/simulation/{$scenario1->slug}");

        $response->assertStatus(403);
    }

    public function test_admins_can_access_others_simulations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $scenario = GameScenario::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->get("/simulation/{$scenario->slug}");

        $response->assertStatus(200);
    }

    public function test_simulation_index_shows_comment_count(): void
    {
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create(['user_id' => $user->id]);
        
        // Add a comment
        $scenario->filamentComments()->create([
            'user_id' => $user->id,
            'comment' => 'Test comment',
            'subject_type' => $scenario->getMorphClass(),
        ]);

        $response = $this->actingAs($user)->get('/simulations');

        $response->assertStatus(200);
        $response->assertSee('1 comment');
    }
}
