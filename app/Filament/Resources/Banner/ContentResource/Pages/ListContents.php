<?php

namespace App\Filament\Resources\Banner\ContentResource\Pages;

use App\Filament\Resources\Banner\ContentResource;
use App\Filament\Resources\Banner\ContentResource\Widgets\BannerStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BannerStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return __('Banner Content Management');
    }
}
