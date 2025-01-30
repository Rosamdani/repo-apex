<?php

namespace App\Filament\Resources\UserAccessPaketResource\Pages;

use App\Filament\Resources\UserAccessPaketResource;
use App\Models\UserAccessPaket;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\ViewRecord;

class ViewUserAccessPaket extends ViewRecord
{
    protected static string $resource = UserAccessPaketResource::class;
    protected static ?string $title = 'Detail Akses Paket Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateStatus')
                ->label('Ubah Status')
                ->action(function (UserAccessPaket $record, array $data): void {
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