<?php

namespace App\Filament\Resources\KabinetResource\Pages;

use App\Filament\Resources\KabinetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKabinet extends EditRecord
{
    protected static string $resource = KabinetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
