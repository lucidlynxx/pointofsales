<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Pages\Actions;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getActions(): array
    {
        return [
            ExportAction::make()
                ->exports([
                    ExcelExport::make('table')
                        ->fromTable()
                        ->withFilename('Laporan Penjualan tanggal ' . date('d M Y')),
                ])
                ->label('Excel')
                ->color('success')
                ->icon('heroicon-o-document-text')
                ->visible(Gate::allows('admin')),
            Actions\Action::make('Pdf')
                ->label('PDF')
                ->color('danger')
                ->icon('heroicon-o-document-text')
                ->url(route('pdf'), shouldOpenInNewTab: true)
                ->visible(Gate::allows('admin')),
            Actions\CreateAction::make(),
        ];
    }

    public array $data_list = [
        'calc_columns' => [
            'total',
            'profit'
        ],
    ];

    protected function getTableContentFooter(): ?View
    {
        return view('table.footer', $this->data_list);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SaleResource\Widgets\SaleOverview::class,
        ];
    }
}
