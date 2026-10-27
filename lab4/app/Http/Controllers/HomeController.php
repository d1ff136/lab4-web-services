<?php

namespace App\Http\Controllers;

use App\Data\CatalogFilterData;
use App\Http\Requests\CatalogFilterRequest;
use App\Models\Product;
use App\Services\CatalogService;

class HomeController extends Controller
{
    public function __construct(private CatalogService $catalog)
    {
    }

    public function index(CatalogFilterRequest $request)
    {
        $filters = CatalogFilterData::fromArray($request->validated());

        return view('home', [
            'featured' => $this->catalog->featured(3),
            'categories' => $this->catalog->categories(),
            'operations' => Product::OPERATIONS,
            'total' => $this->catalog->count(),
            'filters' => $filters,
        ]);
    }
}