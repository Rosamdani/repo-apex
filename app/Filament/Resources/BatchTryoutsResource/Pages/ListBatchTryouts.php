<?php

namespace App\Filament\Resources\BatchTryoutsResource\Pages;

use App\Filament\Resources\BatchTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBatchTryouts extends ListRecords
{
    protected static string $resource = BatchTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Baru')->icon('heroicon-o-plus'),
        ];
    }
}
