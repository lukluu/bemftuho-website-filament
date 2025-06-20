<?php

namespace App\Filament\Resources;

use Dom\Text;
use Carbon\Carbon;
use App\Models\Tag;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\PostResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use App\Filament\Resources\PostResource\RelationManagers\TagsRelationManager;
use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationGroup = 'Post Management';
    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'created_at'];
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
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Title' => $record->title,
            'Dibuat' => $record->created_at->diffForHumans(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),
                    TextInput::make('title')
                        ->unique(ignoreRecord: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')->unique(ignoreRecord: true)->disabled()->dehydrated(),
                    FileUpload::make('post_image')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->directory('posts')
                        ->imageEditor()
                        ->imageCropAspectRatio('3:2')
                        ->imageResizeTargetWidth(768)
                        ->imageResizeTargetHeight(512)
                        ->required(),

                    RichEditor::make('content'),

                    Select::make('tags')
                        ->relationship('tags', 'name')
                        ->placeholder('Add tags...')
                        ->preload()
                        ->multiple()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->hidden(fn(string $operation): bool => $operation === 'edit')
                ]),
                Section::make('Status')
                    ->schema([
                        Toggle::make('is_published'),
                    ])->visible(function (): bool {
                        /** @var \App\Models\User $user */
                        $user = Filament::auth()->user();
                        return $user->isAdmin();
                    }),

            ]);
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
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
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')->sortable()->searchable()
                    ->description(fn(Post $record) => Str::limit(strip_tags($record->content), 50)),
                TextColumn::make('category.name')->sortable()->searchable(),
                TextColumn::make('is_published')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Published' : 'Draft')
                    ->color(fn(bool $state): string => $state ? 'success' : 'warning'),
                TextColumn::make('tags.name')->searchable()->label('Tags')->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Filter by category'),
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Filter by tags'),
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
                // 1. Gambar ditampilkan di tengah
                Fieldset::make('Image Post')
                    ->columns(1)
                    ->schema([
                        ImageEntry::make('post_image')
                            ->label('')
                            ->alignCenter()
                            ->extraAttributes(['class' => 'rounded-lg w-1/2 mx-auto shadow']),
                    ]),



                // 2. Detail lengkap post
                Fieldset::make('Detail Post')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul')
                            ->columnSpan(2)
                            ->weight('bold')
                            ->size(TextEntry\TextEntrySize::Large),

                        TextEntry::make('slug')
                            ->label('Slug'),

                        TextEntry::make('category.name')
                            ->label('Kategori'),

                        TextEntry::make('user.name')
                            ->label('Penulis'),

                        TextEntry::make('is_published')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn(bool $state): string => $state ? 'Published' : 'Draft')
                            ->color(fn(bool $state): string => $state ? 'success' : 'warning'),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime(),

                        TextEntry::make('content')
                            ->label('Konten')
                            ->columnSpan(2)
                            ->markdown(), // jika kamu ingin parsing markdown
                    ]),

                // 3. Section untuk tags
                Fieldset::make('Tags')
                    ->schema([
                        TextEntry::make('tags.name')
                            ->badge()
                            ->separator(', '),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TagsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            // 'view' => Pages\ViewPost::route('/{record}'),
        ];
    }
}
