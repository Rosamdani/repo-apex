<?php

namespace App\Filament\Resources\TryoutsResource\Pages;

use App\Filament\Resources\TryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTryouts extends CreateRecord
{
    protected static string $resource = TryoutsResource::class;
    protected static ?string $title = 'Buat Tryouts Baru';
}
