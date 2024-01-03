<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'qucozobe@brand-app.biz',
            'password' => password_hash('123123', PASSWORD_DEFAULT),
            'name' => 'Henoch Yanuar',
            'role' => 'user',
            'is_active' => 1
        ]);
        DB::table('users')->insert([
            'email' => 'sexawa5054@usoplay.com',
            'password' => password_hash('asdasd', PASSWORD_DEFAULT),
            'name' => 'Ari Swasono',
            'role' => 'user',
            'is_active' => 1
        ]);
        DB::table('users')->insert([
            'email' => 'bedelec282@pyadu.com',
            'password' => password_hash('zxczxc', PASSWORD_DEFAULT),
            'name' => 'Super Admin',
            'role' => 'superadmin',
            'is_active' => 1
        ]);
    }
}
