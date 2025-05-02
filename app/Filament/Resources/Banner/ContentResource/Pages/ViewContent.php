<?php

namespace App\Filament\Resources\Banner\ContentResource\Pages;

use App\Filament\Resources\Banner\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use JoseEspinal\RecordNavigation\Traits\HasRecordNavigation;

class ViewContent extends ViewRecord
{
    use HasRecordNavigation;
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('View Content');
    }
}
