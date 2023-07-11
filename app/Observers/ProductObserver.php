<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->isDirty('image') || is_null($product->image)) {
            if (!is_null($product->getOriginal('image'))) {
                Storage::disk('public')->delete($product->getOriginal('image'));
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        if (!is_null($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
    }
}
