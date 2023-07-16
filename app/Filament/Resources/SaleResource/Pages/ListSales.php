<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\View\View;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public array $data_list = [
        'calc_columns' => [
            'total'
        ],
    ];

    protected function getTableContentFooter(): ?View
    {
        return view('table.footer', $this->data_list);
    }
}
