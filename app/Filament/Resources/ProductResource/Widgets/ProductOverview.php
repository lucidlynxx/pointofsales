<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\SaleItem;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class ProductOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 3;

    protected function getCards(): array
    {
        $saleItemTerbanyak = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->first();

        $saleItemTersedikit = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity')
            ->first();

        return [
            Card::make('Penjualan terbanyak', ucwords($saleItemTerbanyak->product->name))
                ->description($saleItemTerbanyak->total_quantity . ' Item terjual')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),
            Card::make('Penjualan tersedikit', ucwords($saleItemTersedikit->product->name))
                ->description($saleItemTersedikit->total_quantity . ' Item terjual')
                ->descriptionIcon('heroicon-s-trending-down')
                ->color('danger')
        ];
    }
}
