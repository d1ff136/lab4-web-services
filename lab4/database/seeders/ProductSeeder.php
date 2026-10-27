<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Faker\Generator;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public const COUNT = 12;

    public function run(): void
    {
        // Категорії мають існувати раніше за продукти.
        $this->call(CategorySeeder::class);

        $faker = app(Generator::class);
        $faker->unique(true);
        $faker->seed(2107);

        $categories = Category::query()->orderBy('id')->get();
        $perCategory = intdiv(self::COUNT, $categories->count());
        $titles = ProductFactory::TITLES;

        for ($i = 0; $i < self::COUNT; $i++) {
            // Дані завжди генеруються в однаковій послідовності (фіксований seed).
            $data = Product::factory()->raw([
                'category_id' => $categories[intdiv($i, $perCategory)]->id,
                'title' => $titles[$i % count($titles)],
                'status' => $i < 3 ? 'featured' : 'available',
            ]);

            // Наявні продукти не дублюються й не переписуються.
            if (Product::query()->where('slug', $data['slug'])->exists()) {
                continue;
            }

            Product::query()->create($data);
        }
    }
}