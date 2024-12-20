<?php

namespace App\Filament\Resources\TryoutsResource\Pages;

use App\Filament\Resources\TryoutsResource;
use App\Models\Tryouts;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTryouts extends ListRecords
{
    protected static string $resource = TryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Baru')->icon('heroicon-o-plus'),
        ];
    }
}
