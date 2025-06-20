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
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use function Laravel\Prompts\text;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\EventResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Filament\Resources\EventResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\EventMediaRelationManagerResource\RelationManagers\MediaRelationManager;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'start_date', 'end_date', 'event_date', 'location', 'registration_link', 'status'];
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
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kabinet Event')
                    ->schema([
                        Select::make('kabinet_id')
                            ->relationship('kabinet', 'nama_kabinet')
                            ->required(),
                    ]),
                Section::make('Event Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('category_event_id')
                            ->relationship('category', 'name')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->autofocus()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Pendaftaran Mulai')
                            ->placeholder(fn() => now()->format('Y-m-d H:i'))
                            ->native(false)
                            ->format('Y-m-d H:i')
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Pendaftaran Selesai')
                            ->placeholder(fn() => now()->format('Y-m-d H:i'))
                            ->native(false)
                            ->format('Y-m-d H:i')
                            ->required(),

                        Forms\Components\DateTimePicker::make('event_date')
                            ->placeholder(fn() => now()->format('Y-m-d H:i'))
                            ->native(false)
                            ->format('Y-m-d H:i')
                            ->required()
                            ->label('Rencana Kegiatan'),

                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi Kegiatan')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('max_participants')
                            ->numeric(),
                        Forms\Components\TextInput::make('registration_link')
                            ->label('Link Pendaftaran')
                            ->placeholder('https://contoh.com/pendaftaran')
                            ->helperText('Masukkan URL lengkap menuju halaman pendaftaran.')
                            ->maxLength(255)
                            ->url() // validasi agar hanya menerima format URL
                            ->suffixIcon('heroicon-m-link'), // ikon link di ujung input
                        FileUpload::make('image')
                            ->columnSpanFull()
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('3:2')
                            ->imageResizeTargetWidth(768)
                            ->imageResizeTargetHeight(512)
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('event-'),
                            )
                            ->directory('events')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->required(),

                    ]),
                Section::make('Biaya')
                    ->columns(2)
                    ->schema([
                        // Kolom kanan: Toggle Gratis

                        Forms\Components\Toggle::make('is_free')
                            ->label(fn(bool $state): string => $state ? 'Gratis' : 'Berbayar')
                            ->live()
                            ->default(true)
                            ->helperText('Aktifkan jika kegiatan ini tidak dipungut biaya.'),

                        Forms\Components\TextInput::make('price')
                            ->label('Biaya Pendaftaran')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('Masukkan nominal biaya')
                            ->live()
                            ->visible(fn(Forms\Get $get) => ! $get('is_free')) // tampil hanya jika TIDAK gratis
                            ->required(fn(Forms\Get $get) => ! $get('is_free')), // wajib diisi jika tidak gratis
                    ]),
                Section::make('Status Publish')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->default('draft')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                    ])->visible(function (): bool {
                        /** @var \App\Models\User $user */
                        $user = Filament::auth()->user();
                        return $user->isAdmin();
                    }),
                Section::make('Dokumentasi Kegiatan (Isi Setelah Kegiatan Selesai)')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('dokumentasi')
                            ->collection('dokumentasi')
                            ->multiple()
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('dokumentasi-'),
                            ),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                /** @var \App\Models\User $user */
                $user = Filament::auth()->user();

                if (!$user->isAdmin()) {
                    $query->where('user_id', $user->id);
                }
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(fn(Event $record) => Str::limit($record->description, 30)),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('kabinet.nama_kabinet')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->dateTime()
                    ->label('Waktu Pelaksanaan'),
            ])
            ->filters([
                SelectFilter::make('User')
                    ->relationship('user', 'name')
                    ->label('Filter by user')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->visible(function (): bool {
                        /** @var \App\Models\User $user */
                        $user = Filament::auth()->user();
                        return $user->isAdmin();
                    }),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_at_from')
                            ->label('Published At From'),
                        Forms\Components\DatePicker::make('published_at_to')
                            ->label('Published At To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_at_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['published_at_to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),

                            );
                    }),
                Filter::make('is_free')
                    ->label('Free Events Only')
                    ->query(fn(Builder $query): Builder => $query->where('is_free', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
                                    ->label('Pendaftaran Timeline'),
                                TextEntry::make('event_date')
                                    ->label('Jadwal Kegiatan'),
                                TextEntry::make('location')
                                    ->label('Lokasi Kegiatan'),
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
                                TextEntry::make('price')
                                    ->label('Biaya')
                                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                                TextEntry::make('registration_link')
                                    ->label('Registration Link')
                                    ->formatStateUsing(fn($state) => str_starts_with($state, 'http') ? $state : "https://{$state}")
                                    ->url(fn($state): string => $state)
                                    ->openUrlInNewTab() // Optional: opens link in new tab
                                    ->icon('heroicon-o-arrow-top-right-on-square') // Optional: adds link icon
                                    ->color('primary'),
                                TextEntry::make('max_participants'),

                            ]),

                    ]),
                Fieldset::make('Dokumentasi Kegiatan')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('dokumentasi')
                            ->collection('dokumentasi')
                            ->height('200px')
                            ->alignCenter()
                            ->extraAttributes(['class' => 'self-start']),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
