<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game_scenarios', function (Blueprint $table) {
            $table->string('player_a_strategy_1')->default('Strategy 1')->after('player_a_name');
            $table->string('player_a_strategy_2')->default('Strategy 2')->after('player_a_strategy_1');
            $table->string('player_b_strategy_1')->default('Strategy 1')->after('player_b_name');
            $table->string('player_b_strategy_2')->default('Strategy 2')->after('player_b_strategy_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_scenarios', function (Blueprint $table) {
            $table->dropColumn([
                'player_a_strategy_1',
                'player_a_strategy_2',
                'player_b_strategy_1',
                'player_b_strategy_2',
            ]);
        });
    }
};
