<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the users table.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $batchSize = 50;

        $users = [];

        for ($i = 0; $i < $batchSize; $i++) {
            $users[] = [
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->unique()->phoneNumber,
                'profile_photo' => $faker->imageUrl(),
                'certificate' => 'cert_' . $i,
                'password' => Hash::make('password'),
                'email_verified' => $faker->boolean,
                'email_verification_code' => $faker->optional()->uuid,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
