<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = config('catalog.products');

        if ($products) {
            foreach ($products as $productData) {
                Product::firstOrCreate(
                    ['slug' => $productData['slug']],
                    $productData
                );
            }
        }
    }
}
