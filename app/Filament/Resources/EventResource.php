<?php

namespace App\Filament\Resources;

use Dom\Text;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use function Laravel\Prompts\text;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\EventResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EventResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'start_date', 'end_date', 'location', 'registration_link', 'status'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Title' => $record->title,
            'Start Date' => $record->start_date->format('d M Y'),
            'End Date' => $record->end_date->format('d M Y'),
            'Location' => $record->location,
            'Registration Link' => $record->registration_link,
            'Status' => $record->status,
        ];
    }
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Event Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->autofocus()
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->required(),

                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('max_participants')
                            ->numeric(),

                        Forms\Components\Toggle::make('is_free'),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('Rp')
                            ->visible(fn(Forms\Get $get) => !$get('is_free')),

                        Forms\Components\TextInput::make('registration_link')
                            ->maxLength(255),

                        Forms\Components\Select::make('category_event_id')
                            ->relationship('category', 'name')
                            ->required(),

                        Forms\Components\Select::make('kabinet_id')
                            ->relationship('kabinet', 'nama_kabinet')
                            ->required(),
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('event-'),
                            )
                            ->directory('events')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->required(),

                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(fn(Event $record) => Str::limit($record->description, 50)),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kabinet.nama_kabinet')
                    ->sortable(),
                Tables\Columns\TextColumn::make('timeline')
                    ->getStateUsing(function (Event $record) {
                        $start = Carbon::parse($record->start_date)->format('d M Y');
                        $end = Carbon::parse($record->end_date)->format('d M Y');
                        return "$start - $end";
                    })
                    ->label('Event Timeline'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        default => 'primary',
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),

                Tables\Filters\Filter::make('is_free')
                    ->label('Free Events Only')
                    ->query(fn(Builder $query): Builder => $query->where('is_free', true)),
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
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Event Details')
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ])
                    ->schema([
                        ImageEntry::make('image')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                            ])
                            ->height('200px')
                            ->alignCenter()
                            ->extraAttributes(['class' => 'self-start'])
                            ->label('Event Image')
                            ->defaultImageUrl(asset('storage/default/no_image.png')),
                        Grid::make()
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                            ])
                            ->schema([
                                TextEntry::make('title'),
                                TextEntry::make('description'),
                                TextEntry::make('category.name'),
                                TextEntry::make('kabinet.nama_kabinet'),
                                TextEntry::make('timeline')
                                    ->getStateUsing(function (Event $record) {
                                        $start = Carbon::parse($record->start_date)->format('d M Y');
                                        $end = Carbon::parse($record->end_date)->format('d M Y');
                                        return "$start - $end";
                                    })
                                    ->label('Event Timeline'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'draft' => 'warning',
                                        'published' => 'success',
                                        default => 'primary',
                                    })
                                    ->label('Status'),
                                TextEntry::make('is_free')
                                    ->label('Free Event')
                                    // ubah bolean jadi true dan false
                                    ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                                    ->getStateUsing(fn($record) => $record->is_free)
                                    ->color(fn($record) => $record->is_free ? 'success' : 'danger')
                                    ->badge(),
                                TextEntry::make('price'),
                                TextEntry::make('registration_link')
                                    ->label('Registration Link')
                                    ->formatStateUsing(fn($state) => str_starts_with($state, 'http') ? $state : "https://{$state}")
                                    ->url(fn($state): string => $state)
                                    ->openUrlInNewTab() // Optional: opens link in new tab
                                    ->icon('heroicon-o-arrow-top-right-on-square') // Optional: adds link icon
                                    ->color('primary'),
                                TextEntry::make('max_participants'),

                            ]),

                    ])

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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
