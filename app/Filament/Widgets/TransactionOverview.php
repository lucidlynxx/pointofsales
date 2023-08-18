<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TransactionOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Profit', 'Rp ' . number_format(Sale::whereDate('created_at', now())->pluck('total')->sum()))
                ->description('Total transaksi yang didapat hari ini')
                ->descriptionIcon('heroicon-s-information-circle')
                ->color('primary'),
        ];
    }
}
