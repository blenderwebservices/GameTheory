<?php

namespace App\Filament\Resources\GameScenarioResource\Pages;

use App\Filament\Resources\GameScenarioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGameScenario extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = GameScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        
        // Also ensure default strategies/payoffs if needed? 
        // Logic seems handled by default values in Form or defaults in DB.
        
        return $data;
    }
}
