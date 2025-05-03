<?php

namespace App\Enums\Blog;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PostStatus: string implements HasLabel, HasColor, HasIcon
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING => 'Pending Review',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT, self::ARCHIVED => 'gray',
            self::PENDING => 'info',
            self::PUBLISHED => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-m-clock',
            self::PENDING => 'heroicon-m-exclamation-circle',
            self::PUBLISHED => 'heroicon-m-check-circle',
            self::ARCHIVED => 'heroicon-m-archive-box',
        };
    }

    // Static methods for backward compatibility
    public static function options(): array
    {
        return [
            self::DRAFT->value => self::DRAFT->getLabel(),
            self::PENDING->value => self::PENDING->getLabel(),
            self::PUBLISHED->value => self::PUBLISHED->getLabel(),
            self::ARCHIVED->value => self::ARCHIVED->getLabel(),
        ];
    }
}
