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
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
            $table->json('player_a_name')->change();
            $table->json('player_b_name')->change();
            $table->json('player_a_strategy_1')->change();
            $table->json('player_a_strategy_2')->change();
            $table->json('player_b_strategy_1')->change();
            $table->json('player_b_strategy_2')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_scenarios', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->nullable()->change();
            $table->string('player_a_name')->change();
            $table->string('player_b_name')->change();
            $table->string('player_a_strategy_1')->change();
            $table->string('player_a_strategy_2')->change();
            $table->string('player_b_strategy_1')->change();
            $table->string('player_b_strategy_2')->change();
        });
    }
};
