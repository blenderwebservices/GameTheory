<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$scenarios = \App\Models\GameScenario::all();

foreach ($scenarios as $scenario) {
    echo "ID: " . $scenario->id . "\n";
    echo "Name (en): " . ($scenario->getTranslation('name', 'en') ?? 'N/A') . "\n";
    echo "User ID: " . $scenario->user_id . "\n";
    echo "Slug: " . $scenario->slug . "\n";
    echo "-------------------\n";
}
