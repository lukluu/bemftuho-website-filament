<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\LinkAspirasi;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LinkAspirasiResource\Pages;
use App\Filament\Resources\LinkAspirasiResource\RelationManagers;

class LinkAspirasiResource extends Resource
{
    protected static ?string $model = LinkAspirasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';



    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('deskripsi')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(65535),
                Forms\Components\FileUpload::make('hero')
                    ->required()
                    ->columnSpanFull()
                    ->image()
                    ->directory('aspirasi')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('link')
                    ->description(fn(LinkAspirasi $record) => Str::limit($record->deskripsi, 50))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('hero')
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLinkAspirasis::route('/'),
        ];
    }
}
