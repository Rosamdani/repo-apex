<?php

namespace App\Filament\Resources\UserAccessTryoutsResource\Pages;

use App\Filament\Resources\UserAccessTryoutsResource;
use App\Models\UserAccessTryouts;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;

class ViewUserAccessTryouts extends ViewRecord
{
    protected static string $resource = UserAccessTryoutsResource::class;

    protected static ?string $title = 'Permintaan Akses Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateStatus')
                ->label('Ubah Status')
                ->action(function (UserAccessTryouts $record, array $data): void {
                    $record->update(['status' => $data['status']]);
                })
                ->form([
                    ToggleButtons::make('status')
                        ->options([
                            'requested' => 'Permintaan',
                            'accepted' => 'Diterima',
                            'denied' => 'Ditolak',
                        ])
                        ->colors([
                            'requested' => 'warning',
                            'accepted' => 'success',
                            'denied' => 'danger',
                        ])
                        ->inline()
                        ->icons([
                            'requested' => 'heroicon-o-clock',
                            'accepted' => 'heroicon-o-check-circle',
                            'denied' => 'heroicon-o-x-circle',
                        ]),
                ]),

        ];
    }
}
