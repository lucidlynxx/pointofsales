<?php

namespace App\Filament\Widgets;

use App\Models\SaleItem;
use Filament\Widgets\DoughnutChartWidget;
use Illuminate\Support\Facades\DB;

class ProductChart extends DoughnutChartWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Chart';

    protected int | string | array $columnSpan = 1;

    protected function getHeading(): string
    {
        return 'Produk dengan penjualan terbaik';
    }

    protected function getData(): array
    {
        $saleItemTerbanyak = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(7)
            ->get();

        $totalQuantity = $saleItemTerbanyak->map(function ($value) {
            return $value->total_quantity;
        });

        $productName = $saleItemTerbanyak->map(function ($value) {
            return ucwords($value->product->name);
        });

        return [
            'datasets' => [
                [
                    'label' => 'My First Dataset',
                    'data' => $totalQuantity,
                    'backgroundColor' => [
                        'rgb(255, 99, 132)', //Red
                        'rgb(54, 162, 235)', //Blue
                        'rgb(255, 205, 86)', //Yellow
                        'rgb(152,251,152)', //Green
                        'rgb(127, 0, 255)', //Violet
                        'rgb(255, 165, 0)', //Orange
                        'rgb(50, 205, 50)' //Lime
                    ],
                    'hoverOffset' => 5
                ],
            ],
            'labels' => $productName
        ];
    }
}
