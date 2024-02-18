<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Mehedi Hassan',
                'organization_id' => 1,
                'email' => 'mamun@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'id' => 2,
                'name' => 'test admin',
                'organization_id' => 2,
                'email' => 'test@gmail.com',
                'password' => Hash::make('123456'),
            ],
            [
                'id' => 4,
                'name' => 'MD Touhiduzzaman',
                'organization_id' => 1,
                'email' => 'apache.root@gmail.com',
                'password' => Hash::make('123456'),
            ],
        ]);
    }
}
