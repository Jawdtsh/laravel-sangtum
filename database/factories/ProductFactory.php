<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->boolean,
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'dimensions' => $this->faker->randomElement(['10x20x30', '40x50x60', '70x80x90']),
            'category_id' => Category::factory(),
        ];
    }
}
