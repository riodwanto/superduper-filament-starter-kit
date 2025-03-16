<?php

namespace App\Filament\Resources\ContactUsResource\Pages;

use App\Filament\Resources\ContactUsResource;
use Filament\Resources\Pages\ListRecords;

class ListContactUs extends ListRecords
{
    protected static string $resource = ContactUsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
