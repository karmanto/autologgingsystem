<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Karmanto',
            'email' => 'karmanto.s@gmail.com',
            'password' => Hash::make('Qweytr123654$AL'),
            'role' => 'superadmin',
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('@@admin789123'),
            'role' => 'superadmin',
        ]);

        DB::table('data_runtime')->insert([
            'id' => 1,
        ]);
    }
}
