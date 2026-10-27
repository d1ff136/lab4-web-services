<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public const NAMES = ['Повсякденний', 'Діловий', 'Спортивний', 'Домашній'];

    public function run(): void
    {
        foreach (self::NAMES as $name) {
            $slug = Str::slug($name);

            // Повторний запуск не створює дублікатів і не змінює наявних категорій.
            if (Category::query()->where('slug', $slug)->exists()) {
                continue;
            }

            Category::factory()->create(['name' => $name, 'slug' => $slug]);
        }
    }
}