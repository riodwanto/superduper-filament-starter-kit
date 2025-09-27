<?php

namespace App\Filament\Pages\Docs;

use EightyNine\FilamentDocs\Pages\DocsPage;
use Illuminate\Support\Facades\Cache;

class Documentation extends DocsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = '';
    
    protected static ?int $navigationSort = 999999;
    
    protected static ?string $title = 'Documentation';
    
    protected static ?string $slug = 'docs';
    
    public function getTitle(): string
    {
        return 'Documentation';
    }

    public function getHeading(): string
    {
        return 'Documentation';
    }

    public function getSubheading(): ?string
    {
        return 'Complete guide and documentation';
    }

    /**
     * Get the path to markdown files
     */
    protected function getDocsPath(): string
    {
        return resource_path('docs/en');
    }

    protected function getCachedContent(string $filename): string
    {
        return Cache::remember(
            "docs.content.{$filename}",
            3600,
            fn() => $this->loadAndProcessMarkdown($filename)
        );
    }
}