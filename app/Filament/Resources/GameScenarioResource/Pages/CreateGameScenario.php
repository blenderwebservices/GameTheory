<?php

namespace App\Filament\Resources\GameScenarioResource\Pages;

use App\Filament\Resources\GameScenarioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGameScenario extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = GameScenarioResource::class;

    protected ?string $selectedTemplate = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if (isset($data['template'])) {
            $this->selectedTemplate = $data['template'];
            unset($data['template']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->selectedTemplate) {
            $templates = \App\Models\GameScenario::getTemplates();
            $template = $templates[$this->selectedTemplate] ?? null;

            if ($template) {
                $record = $this->getRecord();

                $translatableFields = [
                    'name',
                    'description',
                    'player_a_name',
                    'player_a_strategy_1',
                    'player_a_strategy_2',
                    'player_b_name',
                    'player_b_strategy_1',
                    'player_b_strategy_2'
                ];

                foreach ($translatableFields as $field) {
                    if (isset($template[$field]) && is_array($template[$field])) {
                        // Merge existing (current locale) with template (all locales)
                        // This ensures we keep any manual edits the user made for the active locale
                        // while filling in the missing locales from the template.

                        $currentTranslations = $record->getTranslations($field);
                        $templateTranslations = $template[$field];

                        // Merge: template is base, current overrides (because current comes from the form input)
                        $merged = array_merge($templateTranslations, $currentTranslations);

                        // We can't simply assign array to translatable property if we want to be safe, 
                        // but Model handles it. Spatie's replaceTranslations or setTranslations is safer.
                        $record->replaceTranslations($field, $merged);
                    }
                }

                $record->save();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
