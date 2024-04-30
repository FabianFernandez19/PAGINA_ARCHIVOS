<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            "id" => 12,
            "name" => 'memin',
            "email" => 'ola@ejemplo.com',
            "password" => bcrypt("2222")
        ],
        [
            "id" => 14,
            "name" => 'messi',
            "email" => 'hola@ejemplo.com',
            "password" => bcrypt("2222")
        ]);
    }
}
