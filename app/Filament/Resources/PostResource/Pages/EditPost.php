<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Pages\CreatePost;
use Actions\AddAction;
use GuzzleHttp\Promise\Create;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
