<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /** Назви виробів варіанта 7; номер у списку визначає зображення item-N.svg. */
    public const TITLES = ['Базова сорочка', 'Тепла куртка', 'Легкий костюм', 'Зручний комплект'];

    public const ATTRIBUTES = [
        'Розмір' => ['S', 'M', 'L', 'XL'],
        'Матеріал' => ['Бавовна', 'Вовна', 'Льон'],
        'Колір' => ['Чорний', 'Синій', 'Бежевий'],
    ];

    public function definition(): array
    {
        // Усі виклики Faker виконуються в сталому порядку незалежно від
        // перевизначених значень, тому послідовність залежить лише від seed.
        $attributes = [];
        foreach (self::ATTRIBUTES as $name => $values) {
            $attributes[$name] = $this->faker->randomElement($values);
        }

        $title = $this->faker->randomElement(self::TITLES);
        $price = (int) (round($this->faker->numberBetween(350, 6500) / 10) * 10);
        $note = $this->faker->randomElement([
            'Річ у стані нової, без слідів носіння.',
            'Зберігалась акуратно, оглянути можна за домовленістю.',
            'Відправлення Новою поштою або самовивіз.',
        ]);
        $contactName = $this->faker->name();
        $contactPhone = $this->faker->phoneNumber();
        $contactEmail = $this->faker->safeEmail();
        $publishedAt = $this->faker->dateTimeBetween('2026-09-01', '2026-09-30')->format('Y-m-d');

        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'operation' => Product::OPERATIONS[0],
            'price' => $price,
            'currency' => '₴',
            'attributes' => $attributes,
            // Похідні поля обчислюються після перевизначень (title, category_id).
            'short_description' => fn (array $a) => collect($a['attributes'])
                ->map(fn ($value, $key) => "$key: $value")
                ->implode(' · '),
            'description' => fn (array $a) => sprintf('%s. %s. %s', $a['title'], $a['short_description'], $note),
            'image' => fn (array $a) => 'images/item-'.(((int) array_search($a['title'], self::TITLES)) + 1).'.svg',
            'slug' => fn (array $a) => Str::slug(implode(' ', [
                $a['title'],
                Category::query()->whereKey($a['category_id'])->value('name'),
                ...array_values($a['attributes']),
            ])),
            'contact_name' => $contactName,
            'contact_phone' => $contactPhone,
            'contact_email' => $contactEmail,
            'status' => 'available',
            'published_at' => $publishedAt,
        ];
    }
}