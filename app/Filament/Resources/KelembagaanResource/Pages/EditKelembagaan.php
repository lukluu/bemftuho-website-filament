<?php

namespace App\Filament\Resources\KelembagaanResource\Pages;

use App\Filament\Resources\KelembagaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelembagaan extends EditRecord
{
    protected static string $resource = KelembagaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
