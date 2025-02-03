<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserTryoutsResource\Pages;
use App\Filament\Resources\UserTryoutsResource\RelationManagers;
use App\Models\UserTryouts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTryoutsResource extends Resource
{
    protected static ?string $model = UserTryouts::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tryout_id')
                    ->relationship('tryout', 'nama')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('nilai')
                    ->numeric(),
                Forms\Components\TextInput::make('rank')
                    ->numeric(),
                Forms\Components\TextInput::make('status'),
                Forms\Components\TextInput::make('waktu')
                    ->numeric(),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('question_order')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tryout_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rank')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUserTryouts::route('/'),
            'create' => Pages\CreateUserTryouts::route('/create'),
            'view' => Pages\ViewUserTryouts::route('/{record}'),
            'edit' => Pages\EditUserTryouts::route('/{record}/edit'),
        ];
    }
}
