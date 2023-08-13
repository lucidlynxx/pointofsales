<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;

class SaleItemObserver
{
    protected function updateSaleProfit($saleId)
    {
        $sale = Sale::find($saleId);
        $totalProfit = 0;

        foreach ($sale->saleItems as $saleItem) {
            $product = Product::find($saleItem->product_id);
            $totalProfit += $saleItem->price - ($product->buy_price * $saleItem->quantity);
        }

        $sale->profit = $totalProfit;
        $sale->save();
    }
    /**
     * Handle the SaleItem "created" event.
     */
    public function created(SaleItem $saleItem): void
    {
        $product = Product::find($saleItem->product_id);
        $product->stock = $product->stock - $saleItem->quantity;
        $product->save();

        $this->updateSaleProfit($saleItem->sale_id);
    }

    /**
     * Handle the SaleItem "updated" event.
     */
    public function updated(SaleItem $saleItem): void
    {
        //
    }

    /**
     * Handle the SaleItem "deleted" event.
     */
    public function deleted(SaleItem $saleItem): void
    {
        //
    }

    /**
     * Handle the SaleItem "restored" event.
     */
    public function restored(SaleItem $saleItem): void
    {
        //
    }

    /**
     * Handle the SaleItem "force deleted" event.
     */
    public function forceDeleted(SaleItem $saleItem): void
    {
        //
    }
}
