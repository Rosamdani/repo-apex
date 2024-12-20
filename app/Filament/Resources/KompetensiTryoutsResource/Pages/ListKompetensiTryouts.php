<?php

namespace App\Filament\Resources\KompetensiTryoutsResource\Pages;

use App\Filament\Resources\KompetensiTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKompetensiTryouts extends ListRecords
{
    protected static string $resource = KompetensiTryoutsResource::class;
    protected static ?string $title = 'Kompetensi';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Baru')->icon('heroicon-o-plus'),
        ];
    }
}
