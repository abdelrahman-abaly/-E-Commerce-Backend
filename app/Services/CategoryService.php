<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function getTree(): Collection
    {
        return $this->categoryRepository->getAll(withChildren: true);
    }

    public function create(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }


    public function update(Category $category, array $data): category
    {

        if (isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        if (isset($data["parent_id"]) && $data['parent_id'] == $category->id) {
            throw new \Exception('القسم لا يمكن أن يكون قسماً فرعياً لنفسه');
        }

        return $this->categoryRepository->update($category, $data);
    }
    public function delete(Category $category): bool
    {

        if ($category->products()->exists()) {
            throw new \Exception('لا يمكن حذف قسم يحتوي على منتجات');
        }

        return $this->categoryRepository->delete($category);
    }
}
