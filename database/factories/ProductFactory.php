<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name  = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 50, 5000);
        return [
            'category_id'       => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name'              => ucwords($name),
            'slug'              => Str::slug($name),
            'description'       => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(),
            'price'             => $price,
            'compare_price'     => fake()->boolean(40) ? $price * 1.2 : null,
            'stock'             => fake()->numberBetween(0, 500),
            'sku'               => strtoupper(Str::random(8)),
            'is_active'         => true,
            'is_featured'       => fake()->boolean(20),
        ];
    }
}
