<?php

namespace App\Filament\Resources\UserAnswerResource\Pages;

use App\Filament\Resources\UserAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserAnswer extends ViewRecord
{
    protected static string $resource = UserAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
