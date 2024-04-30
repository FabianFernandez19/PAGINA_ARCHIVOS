<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            "email" => "admin@gmail.com",
            "password" => bcrypt("2222")
        ]);
        $user = User::create([
            "email" => "admin@gmail.com",
            "password" => bcrypt("2222"),
            "name" => "admin",

        ]);
        // Asignación del rol
        $user->assignRole('Administrator');
    }
}
