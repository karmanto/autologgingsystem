<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DataRuntime;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => 'Karmanto',
            'email' => 'karmanto.s@gmail.com',
            'password' => Hash::make('Qweytr123654$AL'),
            'role' => 'superadmin',
        ]);

        User::insert([
            'name' => 'Admin',
            'email' => 'admin@mmj',
            'password' => Hash::make('@@admin789123'),
            'role' => 'admin',
        ]);

        User::insert([
            'name' => 'Operator',
            'email' => 'operator@mmj',
            'password' => Hash::make('@operator123'),
            'role' => 'operator',
        ]);

        DataRuntime::insert([
            'id' => 1,
        ]);
    }
}
