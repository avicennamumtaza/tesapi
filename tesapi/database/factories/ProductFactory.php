<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'price' => fake()->randomFloat(2, 5000, 250000),
            'stock' => fake()->numberBetween(0, 120),
            'is_active' => fake()->boolean(90),
        ];
    }
}
