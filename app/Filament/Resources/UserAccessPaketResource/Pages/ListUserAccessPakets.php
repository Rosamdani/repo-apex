<?php

namespace App\Filament\Resources\UserAccessPaketResource\Pages;

use App\Filament\Resources\UserAccessPaketResource;
use App\Models\UserAccessPaket;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListUserAccessPakets extends ListRecords
{
    protected static string $resource = UserAccessPaketResource::class;
    protected static ?string $title = 'Daftar Akses Paket Tryout';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Buat baru')->label('Buat Baru'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Permintaan'),
            'requested' => Tab::make('Permintaan')
                ->badgeColor('warning')
                ->badge(UserAccessPaket::query()->where('status', 'requested')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'requested')),
            'accepted' => Tab::make('Diterima')
                ->badgeColor('success')
                ->badge(UserAccessPaket::query()->where('status', 'accepted')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'accepted')),
            'denied' => Tab::make('Ditolak')
                ->badgeColor('danger')
                ->badge(UserAccessPaket::query()->where('status', 'denied')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'denied')),
        ];
    }
}