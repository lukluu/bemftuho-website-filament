<?php

namespace App\Filament\Resources;

use Dom\Text;
use stdClass;
use PhpOption\None;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Mahasiswa;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\MahasiswaResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use App\Filament\Resources\MahasiswaResource\Pages\ListMahasiswas;
use App\Filament\Resources\MahasiswaResource\Pages\CreateMahasiswa;
use App\Filament\Resources\MahasiswaResource\RelationManagers\MahasiswaKabinetJabatanRelationManager;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;
    public static function getGloballySearchableAttributes(): array
    {
        return ['nama', 'nim', 'jurusan', 'angkatan'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Nama' => $record->nama,
            'NIM' => $record->nim,
            'Jurusan' => $record->jurusan,
            'Angkatan' => $record->angkatan,
        ];
    }
    protected static ?string $navigationGroup = 'Kelola Pengurus';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        /** @var \App\Models\User $user */
        $user = Filament::auth()->user();

        // Jika admin, tampilkan semua
        if ($user->isAdmin()) {
            return static::getModel()::count();
        }

        // Jika bukan admin, tampilkan count berdasarkan user
        return static::getModel()::where('user_id', $user->id)->count();
    }
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
                        ->unique(ignoreRecord: true)
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
                    Repeater::make('sosmedMhs')
                        ->relationship()
                        ->label('Sosial Media')
                        ->schema([
                            Select::make('sosmed_id')
                                ->relationship('sosmed', 'name')
                                ->label('Nama Sosmed')
                                ->required(),
                            TextInput::make('link')
                                ->label('username')
                                ->placeholder('username')
                                ->required()
                                ->maxLength(255),
                        ]),
                    FileUpload::make('foto_mahasiswa')
                        ->image()
                        ->label('Foto Mahasiswa')
                        ->imageEditor()
                        ->imageCropAspectRatio('3:4')  // Set rasio crop ke 3:4
                        ->imageResizeTargetWidth(300)  // Opsional: set lebar target
                        ->imageResizeTargetHeight(400) // Opsional: set tinggi target
                        ->directory('mahasiswa')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('jurusan')->searchable(),
                Tables\Columns\TextColumn::make('angkatan')->searchable(),
                Tables\Columns\TextColumn::make('kabinetMahasiswaJabatan.jabatan.nama_jabatan')
                    ->searchable()
                    ->label('Jabatan')
                    ->badge()
                    ->color(fn($record) => 'success'),
                Tables\Columns\TextColumn::make('kabinetMahasiswaJabatan.kabinet.nama_kabinet')

                    ->label('Kabinet')->color('success')->badge(),
                Tables\Columns\TextColumn::make('kabinetMahasiswaJabatan.kabinet.periode')
                    ->searchable()
                    ->label('Periode')
                    ->color('success'),
                // kondisi jika tidak ada jabtan

            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // RestoreAction::make(),
                // ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Mahasiswa')
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ])
                    ->schema([
                        ImageEntry::make('foto_mahasiswa')
                            ->defaultImageUrl(asset('storage/default/default.jpg'))
                            ->label('')
                            ->openUrlInNewTab()
                            ->state(function ($record) {
                                // Pastikan $record ada dan field mahasiswa tidak null
                                return $record && $record->mahasiswa ? asset('storage/' . $record->mahasiswa) : null;
                            })
                            ->extraImgAttributes(['loading' => 'lazy'])
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                            ])
                            ->height('200px')
                            ->alignCenter()
                            ->extraAttributes(['class' => 'self-start']),

                        Grid::make()
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                            ])
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('nama')->label('Nama Lengkap'),
                                        TextEntry::make('nim')->label('NIM'),

                                        TextEntry::make('jurusan')->label('Jurusan'),
                                        TextEntry::make('angkatan')->label('Angkatan'),

                                        TextEntry::make('gander')->label('Gender')
                                            ->columnSpan(2),
                                    ])
                                    ->columns(2)
                                    ->extraAttributes(['class' => 'gap-y-1 gap-x-4'])
                            ])
                    ])
            ]);
    }
    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ManageMahasiswas::route('/'),
            'index' => ListMahasiswas::route('/'),
            'create' => CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
            'view' => Pages\ViewMahasiswa::route('/{record}'), // Uncomment jika butuh view page
        ];
    }
    public static function getRelations(): array
    {
        return [
            MahasiswaKabinetJabatanRelationManager::class,
        ];
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->withoutGlobalScopes([
    //             SoftDeletingScope::class,
    //         ]);
    // }
}
