<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameScenario>
 */
class GameScenarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ['en' => $this->faker->sentence(3), 'es' => $this->faker->sentence(3)],
            'description' => ['en' => $this->faker->paragraph, 'es' => $this->faker->paragraph],
            'user_id' => User::factory(),
            'player_a_name' => ['en' => 'Player A', 'es' => 'Jugador A'],
            'player_b_name' => ['en' => 'Player B', 'es' => 'Jugador B'],
            'player_a_strategy_1' => ['en' => 'Strategy 1', 'es' => 'Estrategia 1'],
            'player_a_strategy_2' => ['en' => 'Strategy 2', 'es' => 'Estrategia 2'],
            'player_b_strategy_1' => ['en' => 'Strategy 1', 'es' => 'Estrategia 1'],
            'player_b_strategy_2' => ['en' => 'Strategy 2', 'es' => 'Estrategia 2'],
            'payoff_matrix' => [
                'AA' => [3, 3],
                'AB' => [0, 5],
                'BA' => [5, 0],
                'BB' => [1, 1],
            ],
            'default_payoff_matrix' => [
                'AA' => [3, 3],
                'AB' => [0, 5],
                'BA' => [5, 0],
                'BB' => [1, 1],
            ],
        ];
    }
}
