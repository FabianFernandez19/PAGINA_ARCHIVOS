<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_has_permissions')->insert([
            [
                'role_id' => 1, 
                'permission_id' => 1, 
            ],
            [
                'role_id' => 2, 
                'permission_id' => 2, 
            ],
        ]);
    }
}
