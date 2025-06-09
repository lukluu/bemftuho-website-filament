<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Kelembagaan;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KelembagaanResource\Pages;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\KelembagaanResource\RelationManagers;

class KelembagaanResource extends Resource
{
    protected static ?string $model = Kelembagaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Kelembagaan';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->disabled()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('jurusan')
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
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->imageEditor()
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend('kelembagaan-'),
                    )
                    ->directory('kelembagaan')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelembagaans::route('/'),
            'create' => Pages\CreateKelembagaan::route('/create'),
            'edit' => Pages\EditKelembagaan::route('/{record}/edit'),
        ];
    }
}
