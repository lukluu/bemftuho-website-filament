<?php

namespace App\Filament\Resources\KabinetResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Models\KabinetMahasiswaJabatan;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class MahasiswaKabinetJabatanRelationManager extends RelationManager
{
    protected static string $relationship = 'kabinetMahasiswaJabatan';
    public function getTableHeading(): string
    {
        return 'Daftar Pengurus : ' . $this->ownerRecord->nama_kabinet;
    }
    protected static ?string $recordTitleAttribute = 'jabatan.nama_jabatan';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('jabatan_id')
                            ->label('Jabatan')
                            ->autofocus()
                            ->relationship('jabatan', 'nama_jabatan')
                            ->required()
                            ->createOptionForm([
                                TextInput::make('nama_jabatan')
                                    ->label('Nama Jabatan')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    // ->unique(
                                    //     table: 'jabatans',
                                    //     column: 'nama_jabatan',
                                    //     ignoreRecord: true
                                    // )
                                    // ->rule('unique:jabatans,nama_jabatan')
                                    // ->validationMessages([
                                    //     'unique' => 'Nama jabatan ini sudah digunakan, silahkan pilih nama lain.',
                                    // ])
                                    ->maxLength(255),
                            ])
                            ->rules([
                                function (Get $get) {
                                    return function (string $attribute, $value, Closure $fail) use ($get) {
                                        $kabinetId = $this->ownerRecord->id;

                                        $exists = KabinetMahasiswaJabatan::where('kabinet_id', $kabinetId)
                                            ->where('jabatan_id', $value)
                                            ->where('mahasiswa_id', '!=', $get('mahasiswa_id'))
                                            ->exists();

                                        if ($exists) {
                                            $fail('Jabatan ini sudah terisi oleh mahasiswa lain di kabinet ini.');
                                        }
                                    };
                                }
                            ]),
                        Forms\Components\Select::make('mahasiswa_id')
                            ->label('Mahasiswa')
                            ->relationship('mahasiswa', 'nama')
                            ->required()
                            ->createOptionForm([
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
                            ])
                            ->rules([
                                function (Get $get) {
                                    return function (string $attribute, $value, Closure $fail) use ($get) {
                                        // Validasi: Satu jabatan hanya boleh diisi sekali per kabinet
                                        $existing = KabinetMahasiswaJabatan::where('jabatan_id', $get('jabatan_id'))
                                            ->where('mahasiswa_id', $value)
                                            ->exists();

                                        if ($existing) {
                                            $fail('Mahasiswa ini sudah memiliki jabatan di kabinet yang dipilih.');
                                        }
                                    };
                                }
                            ])
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kabinet.nama_kabinet')
            ->columns([
                Tables\Columns\TextColumn::make('kabinet.nama_kabinet')->searchable(),
                Tables\Columns\TextColumn::make('jabatan.nama_jabatan')->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama')->searchable(),
                Tables\Columns\TextColumn::make('kabinet.periode')->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Struktur')
                    ->outlined()
                    ->color('warning')
                    ->icon('heroicon-o-plus-circle')
                    ->modalHeading('Tambah Struktur untuk ' . $this->ownerRecord->nama_kabinet),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('detech')
                    ->label('Detech')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->forceDelete();

                        \Filament\Notifications\Notification::make()
                            ->title('Relasi dihapus')
                            ->body('Mahasiswa telah didelete dari jabatan & kabinet ini.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
