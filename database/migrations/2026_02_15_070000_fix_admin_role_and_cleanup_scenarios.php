<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\GameScenario;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure admin@admin.com has role 'admin'
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => '$2y$12$UsingHashHereForSafetyEvenIfSeederDidnt',
            ]
        );

        $admin->role = 'admin'; // Force update role
        $admin->save();

        // 2. Delete all scenarios NOT owned by this admin
        // This ensures distinct basic models
        GameScenario::where('user_id', '!=', $admin->id)->delete();
        GameScenario::whereNull('user_id')->delete(); // Just in case nulls aren't caught
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse
    }
};
