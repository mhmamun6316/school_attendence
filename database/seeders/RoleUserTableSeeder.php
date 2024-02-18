<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->insert([
            ['role_id' => 2, 'user_id' => 1],
            ['role_id' => 3, 'user_id' => 2],
            ['role_id' => 2, 'user_id' => 4],
        ]);
    }
}
