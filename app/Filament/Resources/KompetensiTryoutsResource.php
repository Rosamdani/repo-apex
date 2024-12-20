<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KompetensiTryoutsResource\Pages;
use App\Filament\Resources\KompetensiTryoutsResource\RelationManagers;
use App\Models\KompetensiTryouts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KompetensiTryoutsResource extends Resource
{
    protected static ?string $model = KompetensiTryouts::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Tryouts';

    protected static ?string $navigationLabel = 'Kompetensi';

    protected static ?string $slug = 'kompetensi-tryouts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('nama')
                        ->placeholder('Masukkan nama kompetensi')
                        ->unique(KompetensiTryouts::class, 'nama')
                        ->validationMessages([
                            'unique' => 'Nama kompetensi sudah digunakan',
                        ])
                        ->columnSpanFull()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kompetensi')
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
            'index' => Pages\ListKompetensiTryouts::route('/'),
            'create' => Pages\CreateKompetensiTryouts::route('/create'),
            'view' => Pages\ViewKompetensiTryouts::route('/{record}'),
            'edit' => Pages\EditKompetensiTryouts::route('/{record}/edit'),
        ];
    }
}
