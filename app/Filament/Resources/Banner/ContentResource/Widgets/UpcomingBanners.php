<?php

namespace App\Filament\Resources\Banner\ContentResource\Widgets;

use App\Models\Banner\Content;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Filament\Tables;
use Filament\Tables\Table;

class UpcomingBanners extends BaseTableWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Upcoming & Expiring Banners';

    public function table(Table $table): Table
    {
        $now = now();

        return $table
            ->query(
                Content::query()
                    ->where(function ($query) use ($now) {
                        // Upcoming banners (start date in the future)
                        $query->where('is_active', true)
                            ->whereNotNull('start_date')
                            ->where('start_date', '>', $now);
                    })
                    ->orWhere(function ($query) use ($now) {
                        // Expiring banners (end date within the next 7 days)
                        $query->where('is_active', true)
                            ->whereNotNull('end_date')
                            ->where('end_date', '>', $now)
                            ->where('end_date', '<', $now->copy()->addDays(7));
                    })
                    ->orderBy('start_date')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('banners')
                    ->label('')
                    ->collection('banners')
                    ->conversion('thumbnail')
                    ->size(40)
                    ->circular(false),
                Tables\Columns\TextColumn::make('title')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function (Content $record): string {
                        $now = now();
                        if ($record->start_date && $record->start_date > $now) {
                            return 'Upcoming';
                        }
                        if ($record->end_date && $record->end_date > $now && $record->end_date < $now->copy()->addDays(7)) {
                            return 'Expiring Soon';
                        }
                        return 'Active';
                    })
                    ->color(function (Content $record): string {
                        $now = now();
                        if ($record->start_date && $record->start_date > $now) {
                            return 'info';
                        }
                        if ($record->end_date && $record->end_date > $now && $record->end_date < $now->copy()->addDays(7)) {
                            return 'warning';
                        }
                        return 'success';
                    }),
                Tables\Columns\TextColumn::make('schedule')
                    ->formatStateUsing(function (Content $record): string {
                        $now = now();
                        if ($record->start_date && $record->start_date > $now) {
                            return 'Starts: ' . $record->start_date->diffForHumans();
                        }
                        if ($record->end_date && $record->end_date > $now && $record->end_date < $now->copy()->addDays(7)) {
                            return 'Ends: ' . $record->end_date->diffForHumans();
                        }
                        return '-';
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(
                        fn(Content $record): string =>
                        route('filament.admin.resources.banner.contents.edit', ['record' => $record->id])
                    )
                    ->icon('heroicon-m-pencil-square'),
            ]);
    }
}
