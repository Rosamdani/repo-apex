<?php

namespace App\Filament\Resources\UserAccessTryoutsResource\Pages;

use App\Filament\Resources\UserAccessTryoutsResource;
use App\Models\UserAccessTryouts;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;


class ListUserAccessTryouts extends ListRecords
{
    protected static string $resource = UserAccessTryoutsResource::class;

    protected static ?string $title = 'Permintaan Akses Tryout';

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
                ->badge(UserAccessTryouts::query()->where('status', 'requested')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'requested')),
            'accepted' => Tab::make('Diterima')
                ->badgeColor('success')
                ->badge(UserAccessTryouts::query()->where('status', 'accepted')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'accepted')),
            'denied' => Tab::make('Ditolak')
                ->badgeColor('danger')
                ->badge(UserAccessTryouts::query()->where('status', 'denied')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'denied')),
        ];
    }
}
