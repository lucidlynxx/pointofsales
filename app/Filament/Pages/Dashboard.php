<?php

namespace App\Filament\Pages;

use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected function getSubheading(): string | Htmlable | null
    {
        return 'Statistik produk dan penjualan';
    }
}
