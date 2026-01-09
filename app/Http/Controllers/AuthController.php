<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GameScenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        // Fetch default scenarios (for now, assuming scenarios created by admin/user_id 1 are defaults)
        // Or simply fetching all existing scenarios to offer as template
        // Better: Fetch scenarios owned by an admin. For seeding, assume user 1 is admin.
        $defaultScenarios = GameScenario::whereHas('user', function($query) {
            $query->where('role', 'admin');
        })->get();
        
        // If no admin user scenarios found (e.g. first run), fallback to all
        if ($defaultScenarios->isEmpty()) {
            $defaultScenarios = GameScenario::all();
        }

        return view('auth.register', compact('defaultScenarios'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'scenarios' => 'array',
            'scenarios.*' => 'exists:game_scenarios,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        if ($request->has('scenarios')) {
            $scenariosToCopy = GameScenario::whereIn('id', $request->scenarios)->get();
            
            foreach ($scenariosToCopy as $scenario) {
                // Replicate logic
                $newScenario = $scenario->replicate();
                $newScenario->user_id = $user->id;
                $newScenario->name = $scenario->name . ' (Copy)'; // Optional: Append copy? User request didn't specify, but safer to differentiate or keep same name.
                // Keeping same name is cleaner for "default scenarios" feel.
                $newScenario->name = $scenario->name; 
                $newScenario->save();
            }
        }

        Auth::login($user);

        return redirect()->route('home');
    }
}
