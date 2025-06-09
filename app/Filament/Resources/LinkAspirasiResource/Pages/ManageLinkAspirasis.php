<?php

namespace App\Filament\Resources\LinkAspirasiResource\Pages;

use App\Filament\Resources\LinkAspirasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLinkAspirasis extends ManageRecords
{
    protected static string $resource = LinkAspirasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
