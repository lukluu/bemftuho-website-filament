<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\LinkAspirasi;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Console\View\Components\Info;
use Filament\Infolists\Components\ImageEntry;
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
                    ->imageEditor()
                    ->columnSpanFull()
                    ->image()
                    ->directory('aspirasi')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

            ]);
    }
    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Fieldset::make('Link Aspirasi')
    //                 ->schema([
    //                     ImageEntry::make('hero')
    //                         ->columnSpan([
    //                             'sm' => 1,
    //                             'md' => 1,
    //                             'lg' => 1,
    //                         ])
    //                         ->height('200px')
    //                         ->alignCenter()
    //                         ->extraAttributes(['class' => 'self-start'])
    //                         ->label('Event Image')
    //                         ->defaultImageUrl(asset('storage/default/no_image.png')),
    //                     TextEntry::make('link')
    //                         ->label('Link Aspirasi')
    //                         ->icon('heroicon-o-arrow-top-right-on-square') // Optional: adds link icon
    //                         ->color('primary')
    //                         ->size(TextEntry\TextEntrySize::Large),
    //                     TextEntry::make('deskripsi')
    //                         ->label('Deskripsi')
    //                         ->weight('bold')
    //                         ->size(TextEntry\TextEntrySize::Large),

    //                 ])
    //         ]);
    // }

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
                Tables\Actions\ViewAction::make(),
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
