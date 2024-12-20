<?php

namespace App\Filament\Resources\TryoutsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTryoutsRelationManager extends RelationManager
{
    protected static string $relationship = 'userTryouts';
    protected static ?string $title = 'Aktivitas Pengguna';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('nilai')->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paused' => 'Paused',
                        'started' => 'Dimulai',
                        'finished' => 'Selesai',
                        'not_started' => 'Belum Mulai',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paused' => 'gray',
                        'started' => 'warning',
                        'finished' => 'success',
                        'not_started' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('catatan'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
