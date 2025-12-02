<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $scenarios = \App\Models\GameScenario::all();
        return view('welcome', compact('scenarios'));
    }
}
