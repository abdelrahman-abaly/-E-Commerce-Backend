<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parents = [
            ['name' => 'Electronics',  'slug' => 'electronics'],
            ['name' => 'Clothing',     'slug' => 'clothing'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Sports',       'slug' => 'sports'],
            ['name' => 'Books',        'slug' => 'books'],
        ];

        foreach ($parents as $parent) {
            $category = Category::create([...$parent, 'is_active' => true]);

            // كل category فيها 3 sub-categories
            Category::factory(3)->create(['parent_id' => $category->id]);
        }
    }
}
