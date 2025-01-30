<?php

namespace App\Filament\Resources\PaketTryoutResource\Pages;

use App\Filament\Resources\PaketTryoutResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPaketTryout extends ViewRecord
{
    protected static string $resource = PaketTryoutResource::class;

    protected static ?string $title = 'Detail Paket Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
