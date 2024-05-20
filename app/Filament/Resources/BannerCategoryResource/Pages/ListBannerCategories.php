<?php

namespace App\Filament\Resources\BannerCategoryResource\Pages;

use App\Filament\Resources\BannerCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBannerCategories extends ListRecords
{
    protected static string $resource = BannerCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New category'),
        ];
    }
}
