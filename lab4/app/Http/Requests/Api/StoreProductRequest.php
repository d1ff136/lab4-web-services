<?php

namespace App\Http\Requests\Api;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('products', 'slug')],
            'title' => ['required', 'string', 'max:120'],
            'operation' => ['sometimes', 'string', Rule::in(Product::OPERATIONS)],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99', 'decimal:0,2'],
            'currency' => ['sometimes', 'string', 'max:8'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'image' => ['sometimes', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:40'],
            'contact_email' => ['required', 'email', 'max:255'],
            'status' => ['sometimes', Rule::in(['featured', 'available'])],
            'published_at' => ['sometimes', 'date_format:Y-m-d'],
            /** @var array<string, string> Характеристики: назва -> значення */
            'attributes' => ['sometimes', 'array'],
            'attributes.*' => ['string', 'max:100'],
        ];
    }
}