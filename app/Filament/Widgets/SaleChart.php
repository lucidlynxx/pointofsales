<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\Gate;

class SaleChart extends LineChartWidget
{
    protected static ?int $sort = 5;

    protected static ?string $heading = 'Chart';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '325px';

    public static function canView(): bool
    {
        return Gate::allows('admin');
    }

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
            ],
        ],
    ];

    protected function getHeading(): string
    {
        return 'Penjualan minggu ini';
    }

    protected function getData(): array
    {
        $dataProfit = Trend::model(Sale::class)
            ->between(
                start: now()->startOfWeek(),
                end: now()->endOfWeek(),
            )
            ->perDay()
            ->sum('profit');

        $totalProfit = $dataProfit->map(fn (TrendValue $value) => $value->aggregate);

        $dataTransaksi = Trend::model(Sale::class)
            ->between(
                start: now()->startOfWeek(),
                end: now()->endOfWeek(),
            )
            ->perDay()
            ->sum('total');

        $totalTransaksi = $dataTransaksi->map(fn (TrendValue $value) => $value->aggregate);

        $tanggalPerMinggu = $dataProfit->map(fn (TrendValue $value) => $value->date);
        $tanggalPerMingguFormatted = collect($tanggalPerMinggu)->map(function ($tanggal) {
            $tanggal = date('d M Y', strtotime($tanggal));
            return $tanggal;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Total Profit',
                    'data' => $totalProfit,
                    'borderColor' => 'rgb(50, 205, 50)',
                    'backgroundColor' => 'rgb(50, 205, 50)',
                    'fill' => [
                        'target' => 'origin',
                        'above' => 'rgb(50, 205, 50, 0.1)',   // Area will be violet above the origin
                    ]
                ],
                [
                    'label' => 'Total Transaksi',
                    'data' => $totalTransaksi,
                    'borderColor' => 'rgb(127, 0, 255)',
                    'backgroundColor' => 'rgb(127, 0, 255)',
                    'fill' => [
                        'target' => 'origin',
                        'above' => 'rgba(127, 0, 255, 0.1)',   // Area will be violet above the origin
                    ]
                ],
            ],
            'labels' => $tanggalPerMingguFormatted,
        ];
    }
}
