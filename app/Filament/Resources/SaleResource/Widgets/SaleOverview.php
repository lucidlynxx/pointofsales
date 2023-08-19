<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class SaleOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 3;

    public static function canView(): bool
    {
        return Gate::allows('admin');
    }

    protected function getCards(): array
    {
        $startDate = Carbon::today()->subDays(7);
        $sale = Sale::whereDate('created_at', '>=', $startDate)->get();
        return [
            Card::make('Profit', 'Rp ' . number_format($sale->pluck('profit')->sum(), 0, '.', ','))
                ->description('Total profit yang didapat 7 hari terakhir')
                ->descriptionIcon('heroicon-s-cash')
                ->chart($sale->pluck('profit')->toArray())
                ->color('success'),
            Card::make('Total', 'Rp ' . number_format($sale->pluck('total')->sum(), 0, '.', ','))
                ->description('Total transaksi yang didapat 7 hari terakhir')
                ->descriptionIcon('heroicon-s-information-circle')
                ->chart($sale->pluck('total')->toArray())
                ->color('primary'),
            Card::make('Transaksi', $sale->count())
                ->description('Total transaksi yang dilakukan 7 hari terakhir')
                ->descriptionIcon('heroicon-s-switch-horizontal')
                ->color('secondary'),
        ];
    }
}
