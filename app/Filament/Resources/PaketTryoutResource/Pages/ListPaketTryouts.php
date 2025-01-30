<?php

namespace App\Filament\Resources\PaketTryoutResource\Pages;

use App\Filament\Resources\PaketTryoutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaketTryouts extends ListRecords
{
    protected static string $resource = PaketTryoutResource::class;
    protected static ?string $title = 'List Paket Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Paket Tryout'),
        ];
    }
}