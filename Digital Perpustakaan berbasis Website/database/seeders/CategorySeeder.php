<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            DB::table('category')->insert([
                'id' => 1,
                'name' => 'Novel'
            ]);
            DB::table('category')->insert([
                'id' => 2,
                'name' => 'Cerpen'
            ]);
            DB::table('category')->insert([
                'id' => 3,
                'name' => 'Komik'
            ]);
            DB::table('category')->insert([
                'id' => 4,
                'name' => 'Sejarah'
            ]);
            DB::table('category')->insert([
                'id' => 5,
                'name' => 'Majalah'
            ]);
            
        }
    }
}
