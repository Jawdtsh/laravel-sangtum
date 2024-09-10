<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CardsTableSeeder extends Seeder
{
    /**
     * Seed the cards table.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $userIds = DB::table('users')->pluck('id')->toArray();

        $orderIds = DB::table('orders')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            DB::table('cards')->insert([
                'order_id' => $faker->randomElement($orderIds),
                'user_id' => $faker->randomElement($userIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
