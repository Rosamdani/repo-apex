<?php

namespace App\Filament\Resources\UserAccessTryoutsResource\Pages;

use App\Filament\Resources\UserAccessTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserAccessTryouts extends CreateRecord
{
    protected static string $resource = UserAccessTryoutsResource::class;

    protected static ?string $title = 'Buat Permintaan Akses Tryout';
}
