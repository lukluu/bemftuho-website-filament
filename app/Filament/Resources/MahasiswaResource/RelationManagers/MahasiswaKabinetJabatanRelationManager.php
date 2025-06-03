<?php

namespace App\Filament\Resources\MahasiswaResource\RelationManagers;

use Closure;
use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use App\Models\Jabatan;
use App\Models\Kabinet;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Models\KabinetMahasiswaJabatan;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class MahasiswaKabinetJabatanRelationManager extends RelationManager
{
    protected static string $relationship = 'kabinetMahasiswaJabatan';
    protected static ?string $title = 'Kabinet & Jabatan';
    protected static ?string $recordTitleAttribute = 'nama_kabinet';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kabinet_id')
                    ->label('Kabinet')
                    ->relationship('kabinet', 'nama_kabinet')
                    ->required()
                    ->createOptionForm([
                        TextInput::make('nama_kabinet')
                            ->label('Nama Kabinet')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->rules([
                        function () {
                            return function (string $attribute, $value, Closure $fail) {
                                // Validasi: Satu mahasiswa hanya boleh punya satu jabatan per kabinet
                                $existing = KabinetMahasiswaJabatan::where('mahasiswa_id', $this->ownerRecord->id)
                                    ->where('kabinet_id', $value)
                                    ->exists();

                                if ($existing) {
                                    $fail('Mahasiswa ini sudah memiliki jabatan di kabinet yang dipilih.');
                                }
                            };
                        }
                    ]),

                Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama_jabatan')
                    ->required()
                    ->createOptionForm([
                        TextInput::make('nama_jabatan')
                            ->label('Nama Jabatan')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->rules([
                        function (Get $get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                // Validasi: Satu jabatan hanya boleh diisi sekali per kabinet
                                $existing = KabinetMahasiswaJabatan::where('kabinet_id', $get('kabinet_id'))
                                    ->where('jabatan_id', $value)
                                    ->exists();

                                if ($existing) {
                                    $fail('Jabatan ini sudah diisi oleh mahasiswa lain di kabinet yang dipilih.');
                                }
                            };
                        }
                    ])


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kabinet.nama_kabinet')->label('Kabinet'),
                TextColumn::make('jabatan.nama_jabatan')->label('Jabatan'),
                TextColumn::make('kabinet.periode')->label('Periode'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime(),

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
