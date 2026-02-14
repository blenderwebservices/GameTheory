<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\GameScenario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class GameScenariosRelationManager extends RelationManager
{
    protected static string $relationship = 'gameScenarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('template')
                    ->label(__('Choose a Template'))
                    ->options(function () {
                        $templates = GameScenario::getTemplates();
                        return collect($templates)->mapWithKeys(function ($item, $key) {
                            return [$key => $item['name']['en'] ?? $key];
                        });
                    })
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state, $livewire) {
                        if (!$state) {
                            return;
                        }

                        $templates = GameScenario::getTemplates();
                        $template = $templates[$state] ?? null;

                        if (!$template) {
                            return;
                        }

                        // Determine locale
                        $locale = app()->getLocale();

                        // Helper to get translated value or fallback
                        $getTranslated = function ($value) use ($locale) {
                            if (is_array($value)) {
                                return $value[$locale] ?? $value['en'] ?? reset($value);
                            }
                            return $value;
                        };

                        $set('name', $getTranslated($template['name']));
                        $set('description', $getTranslated($template['description']));

                        // Player A
                        $set('player_a_name', $getTranslated($template['player_a_name']));
                        $set('player_a_strategy_1', $getTranslated($template['player_a_strategy_1']));
                        $set('player_a_strategy_2', $getTranslated($template['player_a_strategy_2']));

                        // Player B
                        $set('player_b_name', $getTranslated($template['player_b_name']));
                        $set('player_b_strategy_1', $getTranslated($template['player_b_strategy_1']));
                        $set('player_b_strategy_2', $getTranslated($template['player_b_strategy_2']));

                        // Payoff Matrix
                        if (isset($template['payoff_matrix'])) {
                            foreach ($template['payoff_matrix'] as $key => $values) {
                                $set("payoff_matrix.{$key}.0", $values[0]);
                                $set("payoff_matrix.{$key}.1", $values[1]);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Section::make('Player A')
                            ->schema([
                                Forms\Components\TextInput::make('player_a_name')
                                    ->label('Name')
                                    ->required(),
                                Forms\Components\TextInput::make('player_a_strategy_1')
                                    ->label('Strategy 1')
                                    ->required(),
                                Forms\Components\TextInput::make('player_a_strategy_2')
                                    ->label('Strategy 2')
                                    ->required(),
                            ]),
                        Forms\Components\Section::make('Player B')
                            ->schema([
                                Forms\Components\TextInput::make('player_b_name')
                                    ->label('Name')
                                    ->required(),
                                Forms\Components\TextInput::make('player_b_strategy_1')
                                    ->label('Strategy 1')
                                    ->required(),
                                Forms\Components\TextInput::make('player_b_strategy_2')
                                    ->label('Strategy 2')
                                    ->required(),
                            ]),
                    ]),
                Forms\Components\Section::make('Payoff Matrix')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('payoff_matrix.AA.0')->label('AA 0')->numeric()->required(),
                                Forms\Components\TextInput::make('payoff_matrix.AA.1')->label('AA 1')->numeric()->required(),
                                Forms\Components\TextInput::make('payoff_matrix.AB.0')->label('AB 0')->numeric()->required(),
                                Forms\Components\TextInput::make('payoff_matrix.AB.1')->label('AB 1')->numeric()->required(),
                                Forms\Components\TextInput::make('payoff_matrix.BA.0')->label('BA 0')->numeric()->required(),
                                Forms\Components\TextInput::make('payoff_matrix.BA.1')->label('BA 1')->numeric()->required(),
                                Forms\Components\TextInput::make('payoff_matrix.BB.0')->label('BB 0')->numeric()->required(),
                                Forms\Components\TextInput::make('payoff_matrix.BB.1')->label('BB 1')->numeric()->required(),
                            ]),
                    ])->collapsible(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('player_a_name'),
                Tables\Columns\TextColumn::make('player_b_name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Add from Template')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
