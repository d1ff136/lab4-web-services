<?php

namespace App\Services;

use App\Exceptions\CategoryNotEmpty;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function list(): Collection
    {
        return Category::query()->withCount('products')->orderBy('id')->get();
    }

    public function create(array $data): Category
    {
        return Category::query()->create($data)->loadCount('products');
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->loadCount('products');
    }

    /** @throws CategoryNotEmpty якщо категорія містить продукти */
    public function delete(Category $category): void
    {
        if ($category->products()->exists()) {
            throw new CategoryNotEmpty();
        }

        $category->delete();
    }
}