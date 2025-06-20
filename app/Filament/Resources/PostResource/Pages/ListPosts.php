<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Post')
                ->color('primary')
                ->icon('heroicon-s-plus-circle'),
        ];
    }
    public function getTabs(): array
    {
        /** @var \App\Models\User $user */
        $user = Filament::auth()->user();
        $model = PostResource::getModel();

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

            'Drat' => Tab::make()
                ->label('Draft')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->where('is_published', false)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                )
                ->badge(
                    $model::where('is_published', false)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                        ->count()
                ),

            'Dipublikasikan' => Tab::make()
                ->label('Published')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->where('is_published', true)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                )
                ->badge(
                    $model::where('is_published', true)
                        ->when(! $user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
                        ->count()
                ),
        ];
    }
}
