<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ProfitOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Profit', 'Rp' . number_format(Sale::whereDate('created_at', now())->pluck('profit')->sum(), 0, ',', '.'))
                ->description('Total profit yang didapat hari ini')
                ->descriptionIcon('heroicon-s-cash')
                ->color('success'),
        ];
    }
}
