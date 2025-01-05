<?php

namespace App\Filament\Resources\UserTryoutsResource\Pages;

use App\Filament\Resources\UserTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserTryouts extends ListRecords
{
    protected static string $resource = UserTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
