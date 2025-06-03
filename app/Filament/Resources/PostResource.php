<?php

namespace App\Filament\Resources;

use Dom\Text;
use App\Models\Tag;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
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
use App\Filament\Resources\PostResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\PostResource\RelationManagers\TagsRelationManager;
use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationGroup = 'Post Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'title';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),
                    TextInput::make('title')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')->unique(ignoreRecord: true)->disabled()->dehydrated(),
                    SpatieMediaLibraryFileUpload::make('post_image')
                        ->collection('posts')
                        ->directory('posts')
                        ->required(),

                    RichEditor::make('content'),
                    Toggle::make('is_published'),
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
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('category.name')->sortable()->searchable(),
                TextColumn::make('tags.name')->searchable()->label('Tags')->badge(),
                TextColumn::make('is_published')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Published' : 'Draft')
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                SpatieMediaLibraryImageColumn::make('post_image')->collection('posts')->label('Image')->openUrlInNewTab()->size(50),
            ])
            ->filters([
                Filter::make('Published')
                    ->query(fn(Builder $query): Builder => $query->where('is_published', true)),
                Filter::make('Unpublished')
                    ->query(fn(Builder $query): Builder => $query->where('is_published', false)),
                SelectFilter::make('category')->relationship('category', 'name'),
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
            TagsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
