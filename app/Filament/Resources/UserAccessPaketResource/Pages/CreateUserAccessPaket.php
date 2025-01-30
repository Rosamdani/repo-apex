<?php

namespace App\Filament\Resources\UserAccessPaketResource\Pages;

use App\Filament\Resources\UserAccessPaketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserAccessPaket extends CreateRecord
{
    protected static string $resource = UserAccessPaketResource::class;
    protected static ?string $title = 'Buat Akses Paket Tryout';
}