<?php

namespace App\Filament\Widgets;

use App\Models\GameScenario;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestGameScenarios extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                GameScenario::query()
                    ->when(
                        auth()->user()->role !== 'admin',
                        fn(Builder $query) => $query->where('user_id', auth()->id())
                    )
                    ->withCount('filamentComments')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Scenario Name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Created Date'),
                Tables\Columns\TextColumn::make('filament_comments_count')
                    ->badge()
                    ->label('Comments'),
            ]);
    }
}
