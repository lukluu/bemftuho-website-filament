<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;


use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(function () {
                    $category = $this->getOwnerRecord();
                    return "Posts in Category: {$category->name}";
                })
                    ->description(function () {
                        $category = $this->getOwnerRecord();
                        return "Manage all posts under {$category->name} category";
                    })
                    ->schema([
                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->unique(ignoreRecord: true)->disabled()->dehydrated(),
                        FileUpload::make('post_image')->directory('posts')->required()->visibility('public'),
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
                            ->hidden(fn(string $operation): bool => $operation === 'edit'),

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
    public function table(Table $table): Table
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
            ->recordTitleAttribute('title')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function infolist(Infolist $infolist): Infolist
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
}
