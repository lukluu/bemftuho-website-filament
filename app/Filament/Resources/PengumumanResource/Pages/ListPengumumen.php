<?php

namespace App\Filament\Resources\PengumumanResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\PengumumanResource;

class ListPengumumen extends ListRecords
{
    protected static string $resource = PengumumanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Baru')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
    public function getTabs(): array
    {
        /** @var \App\Models\User $user */
        $user = Filament::auth()->user();
        $model = PengumumanResource::getModel();

        return [
            'Semua' => Tab::make()
                ->label('Semua')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $user->isAdmin()
                        ? $query
                        : $query->where('user_id', $user->id)
                )
                ->badge(
                    $user->isAdmin()
                        ? $model::count()
                        : $model::where('user_id', $user->id)->count()
                ),

            'Dipending' => Tab::make()
                ->label('Pending')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->where('is_active', false)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                )
                ->badge(
                    $model::where('is_active', false)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                        ->count()
                ),

            'Dipublikasikan' => Tab::make()
                ->label('Published')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->where('is_active', true)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                )
                ->badge(
                    $model::where('is_active', true)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                        ->count()
                ),
        ];
    }
}
