<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
    protected static ?string $title = 'Daftar Pengguna';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pengguna'),
            'user' => Tab::make('Peserta')
                ->badgeColor('success')
                ->badge(User::query()->whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('roles', fn($q) => $q->where('name', 'user'))),
            'admin' => Tab::make('Admin')
                ->badgeColor('success')
                ->badge(User::query()->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('roles', fn($q) => $q->where('name', 'admin'))),
        ];
    }
}
