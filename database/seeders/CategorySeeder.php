<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategorySeeder extends Seeder
{


    public function run(): void
    {
        $categories = Category::factory()->count(250)->create();

        $categoriesArray = $categories->pluck('id')->toArray();

        $categories->each(function ($category) use ($categoriesArray) {
            if (random_int(0, 1)) {
                $randomCategoryId = $categoriesArray[array_rand($categoriesArray)];

                $category->parent_id = $randomCategoryId;
                $category->save();
            }
        });
    }
}
