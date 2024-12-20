<?php

namespace App\Filament\Resources\BidangTryoutsResource\Pages;

use App\Filament\Resources\BidangTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBidangTryouts extends ViewRecord
{
    protected static string $resource = BidangTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
