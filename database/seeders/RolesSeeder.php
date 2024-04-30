<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Administrator',
            'guard_name' => 'web',
        ]);
    
        DB::table('roles')->insert([
            'name' => 'User',
            'guard_name' => 'web',
        ]);
        
    }
}
