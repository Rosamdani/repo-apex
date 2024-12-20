<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchTryoutsResource\Pages;
use App\Filament\Resources\BatchTryoutsResource\RelationManagers;
use App\Models\BatchTryouts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchTryoutsResource extends Resource
{
    protected static ?string $model = BatchTryouts::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Tryouts';

    protected static ?string $navigationLabel = 'Batch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->placeholder('Masukkan nama batch')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')->label('Tanggal Mulai')
                    ->native(false)
                    ->minDate(now())
                    ->required(),
                Forms\Components\DatePicker::make('end_date')->label('Tanggal Selesai')
                    ->native(false)
                    ->minDate(fn(?BatchTryouts $record): string => $record?->start_date ?? now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
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
            'index' => Pages\ListBatchTryouts::route('/'),
            'create' => Pages\CreateBatchTryouts::route('/create'),
            'view' => Pages\ViewBatchTryouts::route('/{record}'),
            'edit' => Pages\EditBatchTryouts::route('/{record}/edit'),
        ];
    }
}
