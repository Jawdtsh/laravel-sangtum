<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $rootCategories = Category::all();

        Product::factory(250)->create()->each(function ($product) use ($rootCategories) {
            $product->category_id = $rootCategories->random()->id;
            $product->save();
        });
    }
}
