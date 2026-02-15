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
        // Find the admin user
        $admin = User::where('email', 'admin@admin.com')->first();

        if ($admin) {
            // Delete all game scenarios owned by the admin
            GameScenario::where('user_id', $admin->id)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation needed as this is a cleanup
    }
};
