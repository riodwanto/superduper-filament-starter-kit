<?php

namespace App\Filament\Resources\Banner\ContentResource\Widgets;

use App\Models\Banner\Content;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Filament\Tables;
use Filament\Tables\Table;

class TopPerformingBanners extends BaseTableWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Top Performing Banners';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Content::query()
                    ->where('is_active', true)
                    ->where('impression_count', '>', 0)
                    ->orderByRaw('click_count / impression_count DESC')
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('impression_count')
                    ->label('Views')
                    ->sortable(),
                Tables\Columns\TextColumn::make('click_count')
                    ->label('Clicks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ctr')
                    ->label('CTR')
                    ->formatStateUsing(
                        fn(Content $record): string =>
                        $record->impression_count > 0
                        ? number_format(($record->click_count / $record->impression_count) * 100, 2) . '%'
                        : '0.00%'
                    )
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderByRaw('click_count / impression_count ' . $direction);
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
