<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameScenarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'player_a_name' => $this->player_a_name,
            'player_a_strategy_1' => $this->player_a_strategy_1,
            'player_a_strategy_2' => $this->player_a_strategy_2,
            'player_b_name' => $this->player_b_name,
            'player_b_strategy_1' => $this->player_b_strategy_1,
            'player_b_strategy_2' => $this->player_b_strategy_2,
            'payoff_matrix' => $this->payoff_matrix,
            'default_payoff_matrix' => $this->default_payoff_matrix,
            'default_configuration' => $this->default_configuration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
