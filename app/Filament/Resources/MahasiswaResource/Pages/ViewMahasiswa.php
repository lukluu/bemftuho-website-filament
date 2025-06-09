<?php

namespace App\Filament\Resources\MahasiswaResource\Pages;


use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\MahasiswaResource;


class ViewMahasiswa extends ViewRecord
{
    protected static string $resource = MahasiswaResource::class;
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
