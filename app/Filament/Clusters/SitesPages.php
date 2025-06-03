<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class SitesPages extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Pages';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Sites';
}
