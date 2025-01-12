<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Peserta', \App\Models\User::whereHas('roles', function ($q) {
                $q->where('name', '=', 'user');
            })->count()),
            Stat::make('Total Tryout', \App\Models\Tryouts::count())->description(\App\Models\Tryouts::where('status', 'active')->count() . ' Tryout aktif'),
        ];
    }
}
