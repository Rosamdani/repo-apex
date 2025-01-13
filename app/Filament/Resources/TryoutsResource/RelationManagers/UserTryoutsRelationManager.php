<?php

namespace App\Filament\Resources\TryoutsResource\RelationManagers;

use App\Enum\TryoutStatus;
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
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('nilai')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('catatan')
                    ->maxLength(65535),
                Forms\Components\Select::make('status')
                    ->options([
                        'not_started' => 'Belum Mulai',
                        'started' => 'Sedang Mulai atau Dihentikan Sementara',
                        'finished' => 'Selesai',
                    ])
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('nilai')->sortable(),
                Tables\Columns\TextColumn::make('catatan'),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(function (?TryoutStatus $state): string {
                        return match ($state) {
                            TryoutStatus::FINISHED => 'Selesai',
                            TryoutStatus::STARTED => 'Sedang Berlangsung',
                            TryoutStatus::NOT_STARTED => 'Belum Dimulai',
                            default => 'Tidak Diketahui',
                        };
                    })
                    ->searchable(),
            ])
            ->filters([])
            ->headerActions([])
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
}
