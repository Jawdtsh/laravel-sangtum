<?php
namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'parent_id' => null,
            'status' => $this->faker->boolean,
        ];
    }

    public function withParent($parentId)
    {
        return $this->state(function () use ($parentId) {
            return [
                'parent_id' => $parentId,
            ];
        });
    }
}
