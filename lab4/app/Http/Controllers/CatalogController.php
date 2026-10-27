<?php

namespace App\Http\Controllers;

use App\Data\CatalogFilterData;
use App\Http\Requests\CatalogFilterRequest;
use App\Models\Product;
use App\Services\CatalogService;

class CatalogController extends Controller
{
    public function __construct(private CatalogService $catalog)
    {
    }

    public function index(CatalogFilterRequest $request)
    {
        $filters = CatalogFilterData::fromArray($request->validated());

        return view('catalog.index', [
            'products' => $this->catalog->paginate($filters),
            'categories' => $this->catalog->categories(),
            'operations' => Product::OPERATIONS,
            'filters' => $filters,
        ]);
    }

    public function show(string $slug)
    {
        return view('catalog.show', [
            'product' => $this->catalog->findBySlug($slug),
        ]);
    }
}