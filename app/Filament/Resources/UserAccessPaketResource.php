<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserAccessPaketResource\Pages;
use App\Filament\Resources\UserAccessPaketResource\RelationManagers;
use App\Models\UserAccessPaket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserAccessPaketResource extends Resource
{
    protected static ?string $model = UserAccessPaket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Permintaan Akses Paket Tryout';
    protected static ?string $navigationGroup = 'Tryouts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Nama Peserta')
                        ->relationship('user', 'name')
                        ->searchable(['name', 'email'])
                        ->native(false)
                        ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->email})")
                        ->required(),
                    Forms\Components\Select::make('paket_id')
                        ->relationship('paket', 'paket')
                        ->label('Paket Tryout')
                        ->required(),
                    Forms\Components\ToggleButtons::make('status')
                        ->options([
                            'accepted' => 'Diterima',
                            'denied' => 'Ditolak',
                            'requested' => 'Permintaan',
                        ])
                        ->label('Status')
                        ->colors([
                            'accepted' => 'primary',
                            'denied' => 'danger',
                            'requested' => 'warning',
                        ])
                        ->inline()
                        ->icons([
                            'accepted' => 'heroicon-o-check-circle',
                            'denied' => 'heroicon-o-x-circle',
                            'requested' => 'heroicon-o-clock',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('catatan'),
                    Forms\Components\FileUpload::make('image')
                        ->downloadable()
                        ->label('Bukti Pembayaran')
                        ->disk('public')
                        ->directory('pembayaran/paket')
                        ->image(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('paket.paket')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'accepted' => 'Diterima',
                        'denied' => 'Ditolak',
                        'requested' => 'Permintaan',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('catatan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'requested')->count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserAccessPakets::route('/'),
            'create' => Pages\CreateUserAccessPaket::route('/create'),
            'view' => Pages\ViewUserAccessPaket::route('/{record}'),
        ];
    }
}
