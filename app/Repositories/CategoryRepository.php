<?php

namespace App\Repositories;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function getAll(bool $withChildren = false): Collection
    {
        $query = Category::active()->parents()->ordered();

        if ($withChildren) {
            $query->with('allChildren');
        }
        return $query->get();
    }

    public function create(array $data):Category{
        return Category::create($data);
    }
    public function update(Category $category ,array $data):Category{
       $category->updata($data);
       return $category->fresh();
    }
    public function delete(Category $category):bool{
        
        return $category->delete();
    }
}
