<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create();

        DB::table('books')->delete();
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 5; $j++) {
                DB::table('books')->insert([
                    'code' => "B".fake()->randomNumber(3, true),
                    'title' => $faker->sentence,
                    'id_publisher' => $i,
                    'id_category' => $faker->numberBetween(1, 5), 
                    'id_user' => $faker->numberBetween(1, 3),
                ]);
            }
        }
    }
}
