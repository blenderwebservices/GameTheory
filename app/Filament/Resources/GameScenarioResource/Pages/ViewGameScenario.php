<?php

namespace App\Filament\Resources\GameScenarioResource\Pages;

use App\Filament\Resources\GameScenarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewGameScenario extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = GameScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Parallax\FilamentComments\Actions\CommentsAction::make(),
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
        ];
    }
}
