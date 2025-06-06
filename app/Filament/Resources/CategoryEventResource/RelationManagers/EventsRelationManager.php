<?php

namespace App\Filament\Resources\CategoryEventResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(function () {
                    $ca = $this->getOwnerRecord();
                    return "Category name :{$ca->name}";
                })
                    ->description(function () {
                        $cate = $this->getOwnerRecord();
                        return "Manage all Event under {$cate->name} category";
                    })
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
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

                        Forms\Components\Select::make('kabinet_id')
                            ->relationship('kabinet', 'nama_kabinet')
                            ->required(),
                        FileUpload::make('image')
                            ->image()
                            ->directory('events')
                            ->disk('public')
                            ->imageEditor()
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file) => (string) str($file->getClientOriginalName())->prepend('event-')
                            )
                            ->visibility('public')
                    ])

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Event')
                    ->outlined()
                    ->color('warning')
                    ->icon('heroicon-o-plus-circle')
                    ->modalHeading('Tambah Event untuk ' . $this->ownerRecord->name),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('detach')
                    ->label('Lepaskan Kategori')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (Model $record) {
                        $record->delete();
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Event akan TERHAPUS PERMANEN!!!.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
