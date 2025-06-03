<?php

namespace App\Filament\Resources\MahasiswaResource\Pages;

use Filament\Actions;
use App\Models\Mahasiswa;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\List;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Exports\MahasiswaExporter;
use App\Filament\Imports\MahasiswaImporter;
use Filament\Actions\Exports\Models\Export;
use App\Filament\Resources\MahasiswaResource;

class ListMahasiswas extends ListRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Import')
                ->icon('heroicon-s-arrow-down-on-square')
                ->outlined()
                ->color('success')
                ->importer(MahasiswaImporter::class),
            ExportAction::make()
                ->label('Export')
                ->outlined()
                ->icon('heroicon-s-arrow-up-on-square')
                ->color('danger')
                ->exporter(MahasiswaExporter::class),
            Actions\CreateAction::make()->label('Tambah Mahasiswa')->icon('heroicon-s-plus-circle'),
        ];
    }
}
