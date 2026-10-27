<?php

namespace App\Services;

use App\Data\CatalogFilterData;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CatalogService
{
    /** Рекомендовані продукти (статус featured). */
    public function featured(int $limit = 3): Collection
    {
        return Product::query()
            ->with('category')
            ->where('status', 'featured')
            ->orderBy('id')
            ->limit(max(0, $limit))
            ->get();
    }

    /** Список із пошуком, фільтрами, сортуванням і пагінацією. */
    public function paginate(CatalogFilterData $filters): LengthAwarePaginator
    {
        $query = Product::query()->with('category');

        if ($filters->q !== null) {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $filters->q).'%';
            $query->where(function ($where) use ($like) {
                $where->where('title', 'like', $like)
                    ->orWhere('short_description', 'like', $like);
            });
        }

        if ($filters->category !== null) {
            $query->whereHas('category', fn ($category) => $category->where('name', $filters->category));
        }

        if ($filters->operation !== null) {
            $query->where('operation', $filters->operation);
        }

        // Додатковий ключ id робить порядок стабільним для однакових цін і дат.
        match ($filters->sort) {
            'price_asc' => $query->orderBy('price')->orderBy('id'),
            'price_desc' => $query->orderByDesc('price')->orderBy('id'),
            default => $query->orderByDesc('published_at')->orderByDesc('id'),
        };

        return $query
            ->paginate($filters->perPage, ['*'], 'page', $filters->page)
            ->appends($filters->toQuery());
    }

    /** Продукт за slug; за відсутності запису — ModelNotFoundException (404). */
    public function findBySlug(string $slug): Product
    {
        return Product::query()->with('category')->where('slug', $slug)->firstOrFail();
    }

    public function categories(): Collection
    {
        return Category::query()->orderBy('id')->get();
    }

    public function count(): int
    {
        return Product::query()->count();
    }
}