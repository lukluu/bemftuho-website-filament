<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    // redirect to index page
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
