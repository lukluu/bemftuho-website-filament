<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TagsRelationManager extends RelationManager
{
    protected static string $relationship = 'tags';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(function () {
                    $post = $this->getOwnerRecord();
                    return "Posts Title :{$post->title}";
                })->schema([
                    TextInput::make('name')
                        ->live()
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')->unique(ignoreRecord: true)->disabled()->dehydrated()
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('slug')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
