<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogFilterRequest extends FormRequest
{
    protected $redirectRoute = 'catalog.index';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', Rule::exists('categories', 'name')],
            'operation' => ['nullable', 'string', Rule::in(Product::OPERATIONS)],
            'sort' => ['nullable', 'string', Rule::in(['newest', 'price_asc', 'price_desc'])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', Rule::in([9, 12, 24])],
        ];
    }

    public function messages(): array
    {
        return [
            'q.string' => 'Пошуковий запит має бути рядком.',
            'q.max' => 'Пошуковий запит не може перевищувати 100 символів.',
            'category.exists' => 'Обрану категорію не знайдено.',
            'operation.in' => 'Обрано недопустимий спосіб угоди.',
            'sort.in' => 'Сортування має бути newest, price_asc або price_desc.',
            'page.integer' => 'Номер сторінки має бути цілим числом.',
            'page.min' => 'Номер сторінки має бути не меншим за 1.',
            'per_page.integer' => 'Кількість на сторінці має бути цілим числом.',
            'per_page.in' => 'Кількість на сторінці може дорівнювати лише 9, 12 або 24.',
        ];
    }
}