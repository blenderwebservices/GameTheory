<?php

namespace App\Filament\Resources\GameScenarioResource\Pages;

use App\Filament\Resources\GameScenarioResource;
use Filament\Resources\Pages\Page;

use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class Simulation extends Page
{
    use InteractsWithRecord;

    protected static string $resource = GameScenarioResource::class;

    protected static string $view = 'filament.resources.game-scenario-resource.pages.simulation';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
