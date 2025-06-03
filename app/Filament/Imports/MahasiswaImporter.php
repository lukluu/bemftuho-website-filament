<?php

namespace App\Filament\Imports;

use App\Models\Mahasiswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class MahasiswaImporter extends Importer
{
    protected static ?string $model = Mahasiswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->label('Nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nim')
                ->label('NIM')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('jurusan')
                ->label('Jurusan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('angkatan')
                ->label('Angkatan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('gander')
                ->label('Gander')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Mahasiswa
    {
        // return Mahasiswa::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Mahasiswa();
    }



    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your mahasiswa import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
