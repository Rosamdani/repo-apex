<?php

namespace App\Filament\Resources\UserTryoutsResource\Pages;

use App\Filament\Resources\UserTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserTryouts extends EditRecord
{
    protected static string $resource = UserTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
