<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pengumuman;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PengumumanResource\Pages;
use App\Filament\Resources\PengumumanResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PengumumanResource extends Resource
{
    protected static ?string $model = Pengumuman::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Pengumuman';
    protected static ?int $navigationSort = 5;
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
                Forms\Components\Section::make('Pengumuman')
                    ->description('Buat pengumuman baru')
                    ->schema([
                        Forms\Components\TextInput::make('title')
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
                        RichEditor::make('content')
                            ->required()

                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->previewable(true)
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('pengumuman-'),
                            )
                            ->directory('pengumuman')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                    ]),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label(fn($state): string => $state ? 'Aktif' : 'Tidak Aktif')
                            ->default(false)
                            ->required(),
                    ])->visible(function (): bool {
                        /** @var \App\Models\User $user */
                        $user = Filament::auth()->user();
                        return $user->isAdmin();
                    })
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
                    ->description(fn(Pengumuman $record) => Str::limit(strip_tags($record->content), 50))
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('is_active')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Published' : 'Pending')
                    ->color(fn(bool $state): string => $state ? 'success' : 'warning'),
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
                    })

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
                Section::make('Pengumuman')
                    ->icon('heroicon-m-megaphone')
                    ->description('Detail pengumuman')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Kolom kiri: Gambar
                                ImageEntry::make('image')
                                    ->label('')
                                    ->extraAttributes(['class' => 'w-full h-auto rounded-lg object-cover']),

                                // Kolom kanan: Semua detail (tanpa Grid lagi)
                                TextEntry::make('title')
                                    ->label('Judul')
                                    ->weight('bold'),
                                TextEntry::make('user.name')
                                    ->label('User'),
                                TextEntry::make('created_at')
                                    ->label('Dibuat pada')
                                    ->datetime(),
                                TextEntry::make('is_active')
                                    ->badge()
                                    ->label('Status')
                                    ->formatStateUsing(fn(bool $state): string => $state ? 'Published' : 'Pending')
                                    ->color(fn(bool $state): string => $state ? 'success' : 'warning'),
                                TextEntry::make('content')
                                    ->label('Isi')
                                    ->markdown(),
                            ]),
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
            'index' => Pages\ListPengumumen::route('/'),
            'create' => Pages\CreatePengumuman::route('/create'),
            'edit' => Pages\EditPengumuman::route('/{record}/edit'),
        ];
    }
}
