<?php

namespace App\Filament\Resources\Banner\ContentResource\Pages;

use App\Filament\Resources\Banner\ContentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        return $data;
    }

    public function getTitle(): string
    {
        return __('Create New Banner Content');
    }
}
