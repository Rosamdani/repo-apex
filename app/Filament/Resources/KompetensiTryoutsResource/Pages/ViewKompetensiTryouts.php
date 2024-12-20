<?php

namespace App\Filament\Resources\KompetensiTryoutsResource\Pages;

use App\Filament\Resources\KompetensiTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKompetensiTryouts extends ViewRecord
{
    protected static string $resource = KompetensiTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
