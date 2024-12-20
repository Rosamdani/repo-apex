<?php

namespace App\Filament\Resources\BatchTryoutsResource\Pages;

use App\Filament\Resources\BatchTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBatchTryouts extends ViewRecord
{
    protected static string $resource = BatchTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
