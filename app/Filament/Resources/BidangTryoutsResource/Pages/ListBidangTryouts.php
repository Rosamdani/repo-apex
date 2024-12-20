<?php

namespace App\Filament\Resources\BidangTryoutsResource\Pages;

use App\Filament\Resources\BidangTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBidangTryouts extends ListRecords
{
    protected static string $resource = BidangTryoutsResource::class;
    protected static ?string $title = 'Bidang';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Baru')->icon('heroicon-o-plus'),
        ];
    }
}
