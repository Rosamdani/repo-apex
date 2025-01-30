<?php

namespace App\Filament\Resources\PaketTryoutResource\Pages;

use App\Filament\Resources\PaketTryoutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaketTryout extends EditRecord
{
    protected static string $resource = PaketTryoutResource::class;
    protected static ?string $title = 'Edit Paket Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
