<?php

namespace App\Filament\Resources\UserTryoutsResource\Pages;

use App\Filament\Resources\UserTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserTryouts extends ViewRecord
{
    protected static string $resource = UserTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
