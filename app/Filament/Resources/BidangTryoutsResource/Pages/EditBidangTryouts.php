<?php

namespace App\Filament\Resources\BidangTryoutsResource\Pages;

use App\Filament\Resources\BidangTryoutsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBidangTryouts extends EditRecord
{
    protected static string $resource = BidangTryoutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
