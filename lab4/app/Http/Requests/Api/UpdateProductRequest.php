<?php

namespace App\Http\Requests\Api;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'slug' => [
                'sometimes', 'required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'title' => ['sometimes', 'required', 'string', 'max:120'],
            'operation' => ['sometimes', 'required', 'string', Rule::in(Product::OPERATIONS)],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:9999999999.99', 'decimal:0,2'],
            'currency' => ['sometimes', 'required', 'string', 'max:8'],
            'short_description' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'max:5000'],
            'image' => ['sometimes', 'required', 'string', 'max:255'],
            'contact_name' => ['sometimes', 'required', 'string', 'max:255'],
            'contact_phone' => ['sometimes', 'required', 'string', 'max:40'],
            'contact_email' => ['sometimes', 'required', 'email', 'max:255'],
            'status' => ['sometimes', 'required', Rule::in(['featured', 'available'])],
            'published_at' => ['sometimes', 'required', 'date_format:Y-m-d'],
            /** @var array<string, string> Характеристики: назва -> значення */
            'attributes' => ['sometimes', 'array'],
            'attributes.*' => ['string', 'max:100'],
        ];
    }
}