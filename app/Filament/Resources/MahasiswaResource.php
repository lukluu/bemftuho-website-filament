<?php

namespace App\Filament\Resources;

use Dom\Text;
use stdClass;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Mahasiswa;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Exports\MahasiswaExporter;
use App\Filament\Imports\MahasiswaImporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use App\Filament\Resources\MahasiswaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\MahasiswaResource\RelationManagers;
use App\Filament\Resources\MahasiswaResource\Pages\ListMahasiswas;
use App\Filament\Resources\MahasiswaResource\Pages\CreateMahasiswa;
use App\Filament\Resources\MahasiswaResource\RelationManagers\JabatanRelationManager;
use App\Filament\Resources\MahasiswaResource\RelationManagers\MahasiswaKabinetJabatanRelationManager;
use App\Filament\Resources\MahasiswaResource\RelationManagers\PengurusIntiRelationManager;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kelola Pengurus';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('nim')
                        ->label('NIM')
                        ->required()
                        ->maxLength(255),
                    Select::make('jurusan')
                        ->label('Jurusan')
                        ->options([
                            'S1-Teknik Informatika' => 'S1-Teknik Informatika',
                            'S1-Teknik Elektro' => 'S1-Teknik Elektro',
                            'S1-Teknik Mesin' => 'S1-Teknik Mesin',
                            'S1-Teknik Arsitektur' => 'S1-Teknik Arsitektur',
                            'S1-Teknik Sipil' => 'S1-Teknik Sipil',
                            'S1-Teknik Kelautan' => 'S1-Teknik Kelautan',
                            'S1-Teknik Rekayasa dan Infrastruktur Lingkungan' => 'S1-Teknik Rekayasa dan Infrastruktur Lingkungan',
                            'D3-Teknik Mesin' => 'D3-Teknik Mesin',
                            'D3-Teknik Sipil' => 'D3-Teknik Sipil',
                            'D3-Teknik Elektronika' => 'D3-Teknik Elektronika',
                            'D3-Teknik Arsitektur' => 'D3-Teknik Arsitektur',

                        ])
                        ->required(),
                    Select::make('angkatan')
                        ->label('Angkatan')
                        ->options(
                            collect(range(now()->year, 2021))->mapWithKeys(fn($year) => [$year => $year])->toArray()
                        )
                        ->preload()
                        ->required(),
                    // gender
                    Select::make('gander')
                        ->label('Gender')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->required(),
                    SpatieMediaLibraryFileUpload::make('mahasiswa')
                        ->collection('mahasiswa')
                        ->image(),
                ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('jurusan'),
                Tables\Columns\TextColumn::make('angkatan'),
                Tables\Columns\TextColumn::make('kabinetMahasiswaJabatan.jabatan.nama_jabatan')
                    ->label('Jabatan')
                    ->badge()
                    ->color(fn($record) => 'success'),
                Tables\Columns\TextColumn::make('kabinetMahasiswaJabatan.kabinet.nama_kabinet')
                    ->label('Kabinet')->color('success')->badge(),
                Tables\Columns\TextColumn::make('kabinetMahasiswaJabatan.kabinet.periode')
                    ->label('Periode')
                    ->color('success'),
                // kondisi jika tidak ada jabtan

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ManageMahasiswas::route('/'),
            'index' => ListMahasiswas::route('/'),
            'create' => CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
            // 'view' => Pages\ViewMahasiswa::route('/{record}'), // Uncomment jika butuh view page
        ];
    }
    public static function getRelations(): array
    {
        return [
            MahasiswaKabinetJabatanRelationManager::class,
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
