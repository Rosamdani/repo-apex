<?php

namespace App\Filament\Resources\KompetensiTryoutsResource\Pages;

use App\Filament\Resources\KompetensiTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKompetensiTryouts extends EditRecord
{
    protected static string $resource = KompetensiTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
