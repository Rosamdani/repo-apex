<?php

namespace App\Filament\Resources\UserAccessTryoutsResource\Pages;

use App\Filament\Resources\UserAccessTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserAccessTryouts extends EditRecord
{
    protected static string $resource = UserAccessTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
