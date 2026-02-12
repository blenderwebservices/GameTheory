<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $scenarios = \App\Models\GameScenario::whereNull('slug')->get();

        foreach ($scenarios as $scenario) {
            $nameEn = $scenario->getTranslation('name', 'en');
            $slug = \Illuminate\Support\Str::slug($nameEn);

            // Basic uniqueness check within this migration
            $tempSlug = $slug;
            $count = 1;
            while (\App\Models\GameScenario::where('slug', $tempSlug)->where('id', '!=', $scenario->id)->exists()) {
                $tempSlug = $slug . '-' . $count++;
            }

            $scenario->update(['slug' => $tempSlug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\GameScenario::query()->update(['slug' => null]);
    }
};
