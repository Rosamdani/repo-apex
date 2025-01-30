<?php

namespace App\Filament\Resources\UserAccessPaketResource\Pages;

use App\Filament\Resources\UserAccessPaketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserAccessPaket extends EditRecord
{
    protected static string $resource = UserAccessPaketResource::class;
    protected static ?string $title = 'Ubah Status Akses Paket Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}