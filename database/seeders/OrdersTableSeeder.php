<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrdersTableSeeder extends Seeder
{
    /**
     * Seed the orders table.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $users = DB::table('users')->pluck('id')->toArray();

        $products = DB::table('products')
            ->where('quantity', '>', 0)
            ->where('status', true)
            ->get(['id', 'quantity']);

        $orders = [];
        $batchSize = 250;

        for ($i = 0; $i < $batchSize; $i++) {
            $product = $products->random();
            $orderQuantity = $faker->numberBetween(1, $product->quantity);

            if ($orderQuantity > 0) {
                $orders[] = [
                    'user_id' => $faker->randomElement($users),
                    'product_id' => $product->id,
                    'quantity' => $orderQuantity,
                    'price' => $faker->randomFloat(2, 10, 100),
                    'status' => $faker->randomElement(['pending', 'processed', 'delivered', 'cancelled']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('orders')->insert($orders);
    }
}
