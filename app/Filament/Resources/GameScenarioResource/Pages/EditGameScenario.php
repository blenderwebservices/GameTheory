<?php

namespace App\Filament\Resources\GameScenarioResource\Pages;

use App\Filament\Resources\GameScenarioResource;
use App\Models\GameScenario; // Added this line for type hinting
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameScenario extends EditRecord
{
    use EditRecord\Concerns\Translatable; // Added this line

    protected static string $resource = GameScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(), // Added this line
            Actions\Action::make('simulation') // Changed 'simulate' to 'simulation'
                ->label('Run Simulation')
                ->icon('heroicon-o-play')
                ->url(fn (GameScenario $record): string => GameScenarioResource::getUrl('simulation', ['record' => $record])) // Modified closure
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
