<?php

namespace App\Filament\Resources\Banner\ContentResource\Pages;

use App\Filament\Resources\Banner\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JoseEspinal\RecordNavigation\Traits\HasRecordNavigation;

class EditContent extends EditRecord
{
    use HasRecordNavigation;

    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('Edit Content');
    }
}
