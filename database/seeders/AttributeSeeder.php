<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            'Color' => ['Red', 'Blue', 'Green', 'Black', 'White'],
            'Size'  => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'Weight' => ['250g', '500g', '1kg', '2kg'],
        ];

        foreach ($attributes as $name => $values) {
            $attr = \App\Models\Attribute::create([
                'name'         => $name,
                'display_name' => $name,
            ]);

            foreach ($values as $value) {
                $attr->values()->create([
                    'value'         => $value,
                    'display_value' => $value,
                ]);
            }
        }
    }
}
