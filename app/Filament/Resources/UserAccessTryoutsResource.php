<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserAccessTryoutsResource\Pages;
use App\Filament\Resources\UserAccessTryoutsResource\RelationManagers;
use App\Models\UserAccessTryouts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserAccessTryoutsResource extends Resource
{
    protected static ?string $model = UserAccessTryouts::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?string $navigationGroup = 'Tryouts';

    protected static ?string $navigationLabel = 'Permintaan Akses Tryout';
    protected static ?string $title = 'Permintaan Akses Tryout';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(['name', 'email'])
                    ->native(false)
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->email})")
                    ->required(),
                Forms\Components\Select::make('tryout_id')
                    ->relationship('tryouts', 'nama')
                    ->required(),
                Forms\Components\ToggleButtons::make('status')
                    ->options([
                        'requested' => 'Permintaan',
                        'accepted' => 'Diterima',
                        'denied' => 'Ditolak',
                    ])
                    ->colors([
                        'requested' => 'warning',
                        'accepted' => 'success',
                        'denied' => 'danger',
                    ])
                    ->inline()
                    ->icons([
                        'requested' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-check-circle',
                        'denied' => 'heroicon-o-x-circle',
                    ]),
                Forms\Components\TextInput::make('catatan'),
                Forms\Components\FileUpload::make('image')
                    ->label('Bukti Bayar')
                    ->downloadable()
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tryouts.nama')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'requested' => 'Permintaan',
                        'accepted' => 'Diterima',
                        'denied' => 'Ditolak',
                    ]),
                Tables\Columns\TextColumn::make('catatan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')->label('Bukti Bayar'),
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
                Filter::make('requested')
                    ->query(fn($query) => $query->where('status', 'requested'))
                    ->label('Permohonan Akses'),
                Filter::make('accepted')
                    ->query(fn($query) => $query->where('status', 'accepted'))
                    ->label('Diterima'),
                Filter::make('denied')
                    ->query(fn($query) => $query->where('status', 'denied'))
                    ->label('Ditolak'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUserAccessTryouts::route('/'),
            'create' => Pages\CreateUserAccessTryouts::route('/create'),
            'view' => Pages\ViewUserAccessTryouts::route('/{record}'),
        ];
    }
}
