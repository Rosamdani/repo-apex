<?php

namespace App\Filament\Resources\PaketTryoutResource\Pages;

use App\Filament\Resources\PaketTryoutResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaketTryout extends CreateRecord
{
    protected static string $resource = PaketTryoutResource::class;

    protected static ?string $title = 'Tambah Paket Tryout';
}
