<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidangTryoutsResource\Pages;
use App\Filament\Resources\BidangTryoutsResource\RelationManagers;
use App\Models\BidangTryouts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BidangTryoutsResource extends Resource
{
    protected static ?string $model = BidangTryouts::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Tryouts';

    protected static ?string $navigationLabel = 'Bidang';

    protected static ?string $slug = 'bidang-tryouts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('nama')
                        ->placeholder('Masukkan nama bidang')
                        ->unique(BidangTryouts::class, 'nama')
                        ->validationMessages([
                            'unique' => 'Nama bidang sudah digunakan',
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
                    ->label('Nama Bidang')
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
            'index' => Pages\ListBidangTryouts::route('/'),
            'create' => Pages\CreateBidangTryouts::route('/create'),
            'view' => Pages\ViewBidangTryouts::route('/{record}'),
            'edit' => Pages\EditBidangTryouts::route('/{record}/edit'),
        ];
    }
}
