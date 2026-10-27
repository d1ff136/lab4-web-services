<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function create(array $data): Product
    {
        $product = Product::query()->create($data + [
            'operation' => Product::OPERATIONS[0],
            'currency' => '₴',
            'image' => 'images/item-1.svg',
            'status' => 'available',
            'published_at' => now()->toDateString(),
            'attributes' => [],
        ]);

        return $product->load('category');
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->load('category');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}