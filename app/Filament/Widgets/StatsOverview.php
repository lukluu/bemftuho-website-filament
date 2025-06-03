<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Posts', Post::count())
                ->description('Total posts')
                ->descriptionIcon('heroicon-s-arrow-up-on-square-stack', IconPosition::Before)
                ->chart([1, 3, 5, 10, 15, 20])
                ->color('success')


        ];
    }
}
