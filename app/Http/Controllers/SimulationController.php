<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function index()
    {
        // Fetch all scenarios (or user specific, but request implied showing the updated data)
        // Since we seeded Admin scenarios, and user might want to see them:
        // Current requirement: "show the front only to authenticated users"
        
        if (auth()->user()->role === 'admin') {
            $scenarios = \App\Models\GameScenario::all();
        } else {
            $scenarios = auth()->user()->gameScenarios; 
        }
        return view('simulation.index', compact('scenarios'));
    }

    public function show(\App\Models\GameScenario $gameScenario)
    {
        return view('simulation', compact('gameScenario'));
    }

    public function update(Request $request, \App\Models\GameScenario $gameScenario)
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

        return response()->json(['message' => 'Scenario updated successfully']);
    }

    public function reset(\App\Models\GameScenario $gameScenario)
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
            'message' => 'Scenario reset to defaults',
            'payoff_matrix' => $gameScenario->fresh()->payoff_matrix,
            'player_a_name' => $gameScenario->player_a_name,
            'player_b_name' => $gameScenario->player_b_name,
            'player_a_strategy_1' => $gameScenario->player_a_strategy_1,
            'player_a_strategy_2' => $gameScenario->player_a_strategy_2,
            'player_b_strategy_1' => $gameScenario->player_b_strategy_1,
            'player_b_strategy_2' => $gameScenario->player_b_strategy_2,
        ]);
    }
}
