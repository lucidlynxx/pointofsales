<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class StokProduk extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    protected function getTableQuery(): Builder
    {
        return Product::query()->orderBy('stock');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->formatStateUsing(fn (string $state): string => __(ucwords($state))),
            Tables\Columns\TextColumn::make('categories.name'),
            Tables\Columns\TextColumn::make('stock')
        ];
    }
}
