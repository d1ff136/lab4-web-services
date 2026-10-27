<?php

namespace App\Http\Controllers\Api;

use App\Data\CatalogFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use App\Http\Requests\CatalogFilterRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\CatalogService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $products,
        private CatalogService $catalog,
    ) {
    }

    /**
     * Список продуктів
     *
     * Пошук, фільтри категорії та способу угоди, сортування й пагінація.
     */
    public function index(CatalogFilterRequest $request): AnonymousResourceCollection
    {
        $filters = CatalogFilterData::fromArray($request->validated());

        return ProductResource::collection($this->catalog->paginate($filters));
    }

    /** Створення продукту */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->products->create($request->validated());

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201)
            ->header('Location', route('api.products.show', $product));
    }

    /** Один продукт */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('category'));
    }

    /** Часткове оновлення продукту */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        return new ProductResource($this->products->update($product, $request->validated()));
    }

    /** Видалення продукту */
    public function destroy(Product $product): Response
    {
        $this->products->delete($product);

        return response()->noContent();
    }
}