<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\GameScenario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScenarioApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_get_token()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'token']);
        
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login_and_get_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user', 'token']);
    }

    public function test_authenticated_user_can_list_scenarios()
    {
        $user = User::factory()->create();
        GameScenario::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/scenarios');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_user_can_update_scenario()
    {
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create(['user_id' => $user->id]);

        $newData = [
            'payoff_matrix' => ['AA' => [10, 10], 'AB' => [0, 0], 'BA' => [0, 0], 'BB' => [1, 1]],
            'player_a_name' => 'New Name A',
        ];

        $response = $this->actingAs($user, 'sanctum')
                         ->postJson("/api/v1/scenarios/{$scenario->id}/update", $newData);

        $response->assertStatus(200)
                 ->assertJsonPath('scenario.player_a_name', 'New Name A');
        
        $this->assertEquals(['AA' => [10, 10], 'AB' => [0, 0], 'BA' => [0, 0], 'BB' => [1, 1]], $scenario->fresh()->payoff_matrix);
    }

    public function test_user_can_reset_scenario()
    {
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create([
            'user_id' => $user->id,
            'payoff_matrix' => ['AA' => [0, 0]],
            'default_payoff_matrix' => ['AA' => [5, 5]],
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->postJson("/api/v1/scenarios/{$scenario->id}/reset");

        $response->assertStatus(200);
        $this->assertEquals(['AA' => [5, 5]], $scenario->fresh()->payoff_matrix);
    }
}
