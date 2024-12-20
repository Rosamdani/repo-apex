<?php

namespace App\Filament\Resources\BatchTryoutsResource\Pages;

use App\Filament\Resources\BatchTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatchTryouts extends EditRecord
{
    protected static string $resource = BatchTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
