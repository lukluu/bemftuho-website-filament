<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Jabatan;
use App\Models\Kabinet;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PengurusInti;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Range;
use App\Filament\Resources\KabinetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KabinetResource\RelationManagers;
use App\Filament\Resources\KabinetResource\RelationManagers\JabatanRelationManager;
use App\Filament\Resources\KabinetResource\RelationManagers\PengurusIntiRelationManager;
use App\Models\PenempatanJabatan;
use Filament\Forms\Components\Section;

class KabinetResource extends Resource
{
    protected static ?string $model = Kabinet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kelola Pengurus';
    protected static ?string $navigationLabel = 'Kabinet & Pengurus';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_kabinet')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tagline')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('visi')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('misi')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('periode')
                            ->label('Pilih Tahun Kepengurusan')
                            ->options(function () {
                                $currentYear = now()->year;
                                $years = [];

                                // Generate 5 tahun kebelakang dari tahun sekarang
                                for ($year = $currentYear - 2; $year <= $currentYear; $year++) {
                                    $years["{$year}/" . ($year + 1)] = "{$year}/" . ($year + 1);
                                }

                                return array_reverse($years, true); // Urutkan dari terbaru
                            })
                            ->required()
                            ->default(now()->year . '/' . (now()->year + 1))
                            ->searchable(),
                        FileUpload::make('logo')
                            ->image()
                            ->directory('kabinet') // Folder penyimpanan (di `storage/app/public/articles`)
                            ->maxSize(2048) // Ukuran maksimal dalam KB (2MB)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kabinet')
                    ->badge()->color('success')
                    ->searchable(),
                TextColumn::make('penempatanJabatan')
                    ->label('Jabatan Ketua')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->penempatanJabatan
                            ->firstWhere('jabatan.nama_jabatan', 'Ketua')?->mahasiswa->nama ?? '-'
                    ),
                Tables\Columns\TextColumn::make('periode')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->openUrlInNewTab()
                    ->state(function ($record) {
                        return asset('storage/' . $record->logo); // Return full URL
                    })
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->size(60)
                    ->grow(false),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKabinets::route('/'),
            'create' => Pages\CreateKabinet::route('/create'),
            'edit' => Pages\EditKabinet::route('/{record}/edit'),
        ];
    }
}
