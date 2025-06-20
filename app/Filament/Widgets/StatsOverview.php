<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use App\Models\Event;
use App\Models\Pengumuman;
use Filament\Facades\Filament;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Filament::auth()->user();

        $isAdmin = $user->isAdmin();

        // Hitung data berdasarkan role
        $userCount = $isAdmin ? User::count() : 1;
        $postCount = $isAdmin
            ? Post::count()
            : Post::where('user_id', $user->id)->count();
        $pengumumanCount = $isAdmin
            ? Pengumuman::count()
            : Pengumuman::where('user_id', $user->id)->count();
        $eventCount = $isAdmin
            ? Event::count()
            : Event::where('user_id', $user->id)->count();



        $stats = [
            // Stat::make('Users', User::count())
            //     ->description('Total users')
            //     ->descriptionIcon('heroicon-s-arrow-up-on-square-stack', IconPosition::Before)
            //     ->chart([1, 3, 5, 10, 15, 20])
            //     ->visible($isAdmin)
            //     ->color('success'),
            Stat::make('Posts', $postCount)
                ->description($isAdmin ? 'Total semua post' : 'Post milik Anda')
                ->descriptionIcon('heroicon-s-arrow-up-on-square-stack', IconPosition::Before)
                ->chart([1, 3, 5, 10, 15, 20])
                ->color('success'),
            Stat::make('Pengumuman', $pengumumanCount)
                ->description($isAdmin ? 'Total semua Pengumuman' : 'Pengumuman milik Anda')
                ->descriptionIcon('heroicon-s-arrow-up-on-square-stack', IconPosition::Before)
                ->chart([1, 3, 5, 10, 15, 20])
                ->color('success'),
            Stat::make('Events', $eventCount)
                ->description($isAdmin ? 'Total semua Event' : 'Event milik Anda')
                ->descriptionIcon('heroicon-s-arrow-up-on-square-stack', IconPosition::Before)
                ->chart([1, 3, 5, 10, 15, 20])
                ->color('success'),

        ];
        if ($isAdmin) {
            array_unshift(
                $stats,
                Stat::make('Users', User::count())
                    ->description('Total users')
                    ->descriptionIcon('heroicon-s-arrow-up-on-square-stack', IconPosition::Before)
                    ->chart([1, 3, 5, 10, 15, 20])
                    ->color('success')
            );
        }

        return $stats;
    }
}
