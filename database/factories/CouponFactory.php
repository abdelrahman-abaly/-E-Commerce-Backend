<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'        => strtoupper(Str::random(8)),
            'type'        => fake()->randomElement(['percentage', 'fixed']),
            'value'       => fake()->randomFloat(2, 5, 50),
            'usage_limit' => fake()->numberBetween(10, 100),
            'is_active'   => true,
            'starts_at'   => now(),
            'expires_at'  => now()->addMonths(3),
        ];
    }
}
