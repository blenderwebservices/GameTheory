<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\GameScenario;
use App\Http\Resources\V1\GameScenarioResource;
use Illuminate\Http\Request;

class GameScenarioController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'admin') {
            $scenarios = GameScenario::all();
        } else {
            $scenarios = $user->gameScenarios; 
        }
        
        return GameScenarioResource::collection($scenarios);
    }

    public function show(GameScenario $gameScenario)
    {
        return new GameScenarioResource($gameScenario);
    }

    public function update(Request $request, GameScenario $gameScenario)
    {
        $validated = $request->validate([
            'payoff_matrix' => 'required|array',
            'player_a_name' => 'nullable|string',
            'player_b_name' => 'nullable|string',
            'player_a_strategy_1' => 'nullable|string',
            'player_a_strategy_2' => 'nullable|string',
            'player_b_strategy_1' => 'nullable|string',
            'player_b_strategy_2' => 'nullable|string',
        ]);

        $gameScenario->update(array_filter($validated, function($value) {
            return !is_null($value);
        }));

        return response()->json([
            'message' => 'Escenario actualizado con éxito',
            'scenario' => new GameScenarioResource($gameScenario)
        ]);
    }

    public function reset(GameScenario $gameScenario)
    {
        $updates = [];
        
        if ($gameScenario->default_payoff_matrix) {
            $updates['payoff_matrix'] = $gameScenario->default_payoff_matrix;
        }

        if ($gameScenario->default_configuration) {
            $config = $gameScenario->default_configuration;
            $updates['player_a_name'] = $config['player_a_name'] ?? $gameScenario->player_a_name;
            $updates['player_b_name'] = $config['player_b_name'] ?? $gameScenario->player_b_name;
            $updates['player_a_strategy_1'] = $config['player_a_strategy_1'] ?? $gameScenario->player_a_strategy_1;
            $updates['player_a_strategy_2'] = $config['player_a_strategy_2'] ?? $gameScenario->player_a_strategy_2;
            $updates['player_b_strategy_1'] = $config['player_b_strategy_1'] ?? $gameScenario->player_b_strategy_1;
            $updates['player_b_strategy_2'] = $config['player_b_strategy_2'] ?? $gameScenario->player_b_strategy_2;
        }

        if (!empty($updates)) {
            $gameScenario->update($updates);
        }

        return response()->json([
            'message' => 'Escenario restablecido a los valores predeterminados',
            'scenario' => new GameScenarioResource($gameScenario->fresh())
        ]);
    }
}
