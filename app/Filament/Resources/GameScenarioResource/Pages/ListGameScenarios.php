<?php

namespace App\Filament\Resources\GameScenarioResource\Pages;

use App\Filament\Resources\GameScenarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameScenarios extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = GameScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
