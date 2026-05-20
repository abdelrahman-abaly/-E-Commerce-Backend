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
        Product::factory(50)->create()->each(function ($product) {
            // إضافة صور
            $product->images()->createMany([
                ['image_path' => 'products/placeholder.jpg', 'is_primary' => true,  'sort_order' => 0],
                ['image_path' => 'products/placeholder.jpg', 'is_primary' => false, 'sort_order' => 1],
            ]);
        });
    }
}
