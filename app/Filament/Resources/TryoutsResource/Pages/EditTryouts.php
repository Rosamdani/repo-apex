<?php

namespace App\Filament\Resources\TryoutsResource\Pages;

use App\Filament\Resources\TryoutsResource;
use App\Models\Tryouts;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditTryouts extends EditRecord
{
    protected static string $resource = TryoutsResource::class;
    protected static ?string $title = 'Edit Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('View Tryout')->icon('heroicon-o-eye'),
            Action::make('delete')
                ->color('danger')
                ->label('Delete Tryout')
                ->action(fn(Tryouts $record) => $record->delete())
                ->requiresConfirmation(),
        ];
    }
}
