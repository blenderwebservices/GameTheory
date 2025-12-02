<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function show(\App\Models\GameScenario $gameScenario)
    {
        return view('simulation', compact('gameScenario'));
    }
}
