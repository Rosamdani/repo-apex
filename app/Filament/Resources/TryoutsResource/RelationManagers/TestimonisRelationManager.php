<?php

namespace App\Filament\Resources\TryoutsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestimonisRelationManager extends RelationManager
{
    protected static string $relationship = 'testimonis';
    protected static ?string $title = 'Testimoni';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('testimoni')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->testimonis ? $ownerRecord->testimonis->count() : null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('testimoni')
            ->columns([
                Tables\Columns\TextColumn::make('tryout.nama')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('testimoni')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nilai')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
