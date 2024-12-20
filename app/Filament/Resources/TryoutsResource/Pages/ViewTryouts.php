<?php

namespace App\Filament\Resources\TryoutsResource\Pages;

use App\Filament\Resources\TryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTryouts extends ViewRecord
{
    protected static string $resource = TryoutsResource::class;
    protected static ?string $title = 'Detail Tryout';


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit Tryout')->icon('heroicon-o-pencil-square'),
        ];
    }
}
