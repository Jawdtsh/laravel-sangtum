<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            UsersTableSeeder::class,
            OrdersTableSeeder::class,
            CardsTableSeeder::class,
        ]);
    }
}
