<?php

namespace App\Filament\Resources\MahasiswaResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Jabatan;
use App\Models\Kabinet;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class JabatanRelationManager extends RelationManager
{
    protected static string $relationship = 'penempatanJabatan';

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make("Penempatan Jabatan untuk: {$this->getOwnerRecord()->nama}")
                ->description("Kelola semua jabatan yang dimiliki oleh {$this->getOwnerRecord()->nama}")
                ->schema([
                    Select::make('jabatan_id')
                        ->label('Jabatan')

                        ->relationship('jabatan', 'nama_jabatan')
                        ->options(Jabatan::all()->pluck('nama_jabatan', 'id'))
                        ->required()
                        ->rules([
                            fn(callable $get) =>
                            function ($attribute, $value, $fail) use ($get) {
                                $kabinetId = $get('kabinet_id');
                                $mahasiswaId = $get('mahasiswa_id');

                                if (! $kabinetId) return;

                                $exists = DB::table('penempatan_jabatan')
                                    ->where('jabatan_id', $value)
                                    ->where('kabinet_id', $kabinetId)
                                    ->exists();

                                if ($exists) {
                                    $fail('Kombinasi jabatan dan kabinet ini sudah digunakan.');
                                }
                            }
                        ])
                        ->createOptionForm([
                            TextInput::make('nama_jabatan')
                                ->required()
                                ->maxLength(255)
                                ->unique('jabatans', 'nama_jabatan'),
                        ]),
                    Forms\Components\Select::make('kabinet_id')
                        ->label('Kabinet')
                        ->relationship('kabinet', 'nama_kabinet')
                        ->options(Kabinet::all()->pluck('nama_kabinet', 'id'))
                        ->required()
                        // ->unique('penempatan_jabatan', 'kabinet_id')
                        ->rules([
                            fn(callable $get) =>
                            function ($attribute, $value, $fail) use ($get) {
                                $jabatanId = $get('jabatan_id');
                                if (! $jabatanId) return;
                                $exists = DB::table('penempatan_jabatan')
                                    ->where('kabinet_id', $value)
                                    ->where('jabatan_id', $jabatanId)
                                    ->exists();

                                if ($exists) {
                                    $fail('Kombinasi jabatan dan kabinet ini sudah digunakan.');
                                }
                            }
                        ])
                        ->createOptionForm([
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
                                        ->unique(ignoreRecord: true)
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
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->required(),
                                ])

                        ]),
                ])
                ->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_jabatan')
            ->columns([
                TextColumn::make('jabatan.nama_jabatan')->label('Jabatan')->badge()->color('success'),
                TextColumn::make('kabinet.nama_kabinet')->label('Kabinet')->badge()->color('success'),
                TextColumn::make('kabinet.periode')->label('Periode')->badge()->color('success'),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
