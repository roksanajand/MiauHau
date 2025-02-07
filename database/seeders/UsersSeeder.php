<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingUser = DB::table('users')->where('email', 'john.doe@gmail.com')->first();

        if (!$existingUser) {
            DB::table('users')->insert([
                'name' => 'John Doe',
                'email' => 'john.doe@gmail.com',
                'password' => bcrypt('secret'),
            ]);
        }


        $doraExists = DB::table('users')->where('email', 'dora.smith@gmail.com')->exists();

        if (!$doraExists) {
            DB::table('users')->insert([
                'name' => 'Dorothéa Smith',
                'email' => 'dora.smith@gmail.com',
                'password' => bcrypt('dora123'),
            ]);
        }

        $martinExists = DB::table('users')->where('email', 'martin.smithson@gmail.com')->exists();

        if (!$martinExists) {
            DB::table('users')->insert([
                'name' => 'Martin Smithson',
                'email' => 'martin.smithson@gmail.com',
                'password' => bcrypt('123'),
            ]);
        }

        $adminExists = DB::table('users')->where('email', 'adminStrony@gmail.com')->exists();

        if (!$adminExists) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'adminStrony@gmail.com',
                'password' => bcrypt('admin123'),
            ]);
        }


        $aniaExists = DB::table('users')->where('email', 'ania.kowalska@gmail.com')->exists();

        if (!$martinExists) {
            DB::table('users')->insert([
                'name' => 'Ania Kowalska',
                'email' => 'ania.kowalska@gmail.com',
                'password' => bcrypt('1234'),
            ]);
        }

    }
}
