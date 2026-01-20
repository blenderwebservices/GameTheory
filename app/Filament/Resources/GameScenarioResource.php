<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameScenarioResource\Pages;
use App\Filament\Resources\GameScenarioResource\RelationManagers;
use App\Models\GameScenario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Resources\Concerns\Translatable;

class GameScenarioResource extends Resource
{
    use Translatable;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    protected static ?string $model = GameScenario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('template')
                    ->label('Choose a Template')
                    ->options(function () {
                        $templates = GameScenario::getTemplates();
                        return collect($templates)->mapWithKeys(function ($item, $key) {
                            // Use English name for the label, or fallback
                            return [$key => $item['name']['en'] ?? $key];
                        });
                    })
                    ->live()
                    ->dehydrated(true)
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
                        $locale = property_exists($livewire, 'activeLocale') ? $livewire->activeLocale : app()->getLocale();

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
                                    ->default('Player A')
                                    ->required(),
                                Forms\Components\TextInput::make('player_a_strategy_1')
                                    ->label('Strategy 1')
                                    ->default('Strategy 1')
                                    ->required(),
                                Forms\Components\TextInput::make('player_a_strategy_2')
                                    ->label('Strategy 2')
                                    ->default('Strategy 2')
                                    ->required(),
                            ]),
                        Forms\Components\Section::make('Player B')
                            ->schema([
                                Forms\Components\TextInput::make('player_b_name')
                                    ->label('Name')
                                    ->default('Player B')
                                    ->required(),
                                Forms\Components\TextInput::make('player_b_strategy_1')
                                    ->label('Strategy 1')
                                    ->default('Strategy 1')
                                    ->required(),
                                Forms\Components\TextInput::make('player_b_strategy_2')
                                    ->label('Strategy 2')
                                    ->default('Strategy 2')
                                    ->required(),
                            ]),
                    ]),
                Forms\Components\Section::make('Payoff Matrix')
                    ->description('Enter the payoffs for each outcome. Format: (Player A Payoff, Player B Payoff)')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Section::make('Top / Left')
                                    ->schema([
                                        Forms\Components\TextInput::make('payoff_matrix.AA.0')
                                            ->label('Player A')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('payoff_matrix.AA.1')
                                            ->label('Player B')
                                            ->numeric()
                                            ->required(),
                                    ])->columns(2),
                                Forms\Components\Section::make('Top / Right')
                                    ->schema([
                                        Forms\Components\TextInput::make('payoff_matrix.AB.0')
                                            ->label('Player A')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('payoff_matrix.AB.1')
                                            ->label('Player B')
                                            ->numeric()
                                            ->required(),
                                    ])->columns(2),
                                Forms\Components\Section::make('Bottom / Left')
                                    ->schema([
                                        Forms\Components\TextInput::make('payoff_matrix.BA.0')
                                            ->label('Player A')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('payoff_matrix.BA.1')
                                            ->label('Player B')
                                            ->numeric()
                                            ->required(),
                                    ])->columns(2),
                                Forms\Components\Section::make('Bottom / Right')
                                    ->schema([
                                        Forms\Components\TextInput::make('payoff_matrix.BB.0')
                                            ->label('Player A')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('payoff_matrix.BB.1')
                                            ->label('Player B')
                                            ->numeric()
                                            ->required(),
                                    ])->columns(2),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Scenario Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('player_a_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('player_b_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('user.name')
                    ->label('Creator')
                    ->collapsible(),
            ])
            ->defaultGroup('user.name')
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGameScenarios::route('/'),
            'create' => Pages\CreateGameScenario::route('/create'),
            'view' => Pages\ViewGameScenario::route('/{record}'),
            'edit' => Pages\EditGameScenario::route('/{record}/edit'),
            'simulation' => Pages\Simulation::route('/{record}/simulation'),
        ];
    }
}
