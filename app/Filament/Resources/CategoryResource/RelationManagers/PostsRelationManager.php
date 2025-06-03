<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;


use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
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
                        SpatieMediaLibraryFileUpload::make('post_image')->collection('posts')->directory('posts')->required(),
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
                            ->hidden(fn(string $operation): bool => $operation === 'edit'),

                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
