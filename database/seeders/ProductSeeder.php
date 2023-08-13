<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'indomie goreng',
            'slug' => 'indomie-goreng',
            'description' => 'mie instan merk indomie dengan rasa mie goreng',
            'buy_price' => 3000,
            'sell_price' => 3500,
            'stock' => 60,
            'unit_id' => 1,
            'barcode' => random_int(1000000000, 9999999999)
        ]);

        Product::create([
            'name' => 'indomie ayam geprek',
            'slug' => 'indomie-ayam-geprek',
            'description' => 'mie instan merk indomie dengan rasa ayam geprek',
            'buy_price' => 4000,
            'sell_price' => 4500,
            'stock' => 60,
            'unit_id' => 1,
            'barcode' => random_int(1000000000, 9999999999)
        ]);
    }
}
