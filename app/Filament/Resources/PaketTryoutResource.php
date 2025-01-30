<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaketTryoutRelationManagerResource\RelationManagers\TryoutsRelationManager;
use App\Filament\Resources\PaketTryoutResource\Pages;
use App\Filament\Resources\PaketTryoutResource\RelationManagers;
use App\Models\PaketTryout;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaketTryoutResource extends Resource
{
    protected static ?string $model = PaketTryout::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationLabel = 'Paket Tryout';
    protected static ?string $navigationGroup = 'Tryouts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()->schema([
                    Tabs\Tab::make('Paket Tryout')->schema([
                        Forms\Components\TextInput::make('paket')
                            ->label('Nama Paket')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar')
                            ->image(),
                        Forms\Components\TextInput::make('harga')
                            ->label('Harga Paket')
                            ->minValue(0)
                            ->hint('Kosongkan jika gratis (free)')
                            ->numeric(),
                        Forms\Components\Select::make('tryouts')
                            ->relationship('tryouts', 'nama')
                            ->multiple()
                            ->preload()
                            ->required(),
                        Forms\Components\ToggleButtons::make('status')
                            ->options([
                                'active' => 'Aktif',
                                'nonaktif' => 'Non Aktif',
                            ])
                            ->colors([
                                'active' => 'success',
                                'nonaktif' => 'danger',
                            ])
                            ->inline()
                            ->label('Status')
                            ->required()
                            ->default('nonaktif')
                            ->icons([
                                'active' => 'heroicon-o-check-circle',
                                'nonaktif' => 'heroicon-o-x-circle',
                            ]),
                    ]),
                    Tabs\Tab::make('Detail')->schema([
                        Forms\Components\RichEditor::make('detail'),
                        Forms\Components\TextInput::make('url')->url()->label('Link Pembelian')->placeholder('https://link_pembelian.com'),
                    ])
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paket')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('harga')
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
            TryoutsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaketTryouts::route('/'),
            'create' => Pages\CreatePaketTryout::route('/create'),
            'view' => Pages\ViewPaketTryout::route('/{record}'),
            'edit' => Pages\EditPaketTryout::route('/{record}/edit'),
        ];
    }
}