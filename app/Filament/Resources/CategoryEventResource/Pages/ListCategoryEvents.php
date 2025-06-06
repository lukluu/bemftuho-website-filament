<?php

namespace App\Filament\Resources\CategoryEventResource\Pages;

use App\Filament\Resources\CategoryEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryEvents extends ListRecords
{
    protected static string $resource = CategoryEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
