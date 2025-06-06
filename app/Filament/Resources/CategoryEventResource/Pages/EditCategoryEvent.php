<?php

namespace App\Filament\Resources\CategoryEventResource\Pages;

use App\Filament\Resources\CategoryEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryEvent extends EditRecord
{
    protected static string $resource = CategoryEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
