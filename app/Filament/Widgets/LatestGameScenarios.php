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
                    ->label('Nombre del Escenario'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creador')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Fecha de Integración'),
                Tables\Columns\TextColumn::make('filament_comments_count')
                    ->badge()
                    ->label('Comentarios'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_simulation')
                    ->label('Simular')
                    ->icon('heroicon-o-play')
                    ->url(fn(GameScenario $record): string => route('simulation.show', $record))
                    ->openUrlInNewTab(),
            ])
            ->groups([
                'user.name',
            ])
            ->defaultGroup(
                auth()->user()->role === 'admin' ? 'user.name' : null
            );
    }
}
