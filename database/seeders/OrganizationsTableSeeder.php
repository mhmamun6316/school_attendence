<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->insert([
            [
                'id' => 1,
                'name' => 'Krc Tech',
                'address' => 'Dhaka',
                'parent_id' => 0,
            ],
            [
                'id' => 2,
                'name' => 'St. Joesph',
                'address' => 'Mohammadpur',
                'parent_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Model College',
                'address' => 'Uttara',
                'parent_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'MC-1',
                'address' => 'Sector10',
                'parent_id' => 3,
            ],
            [
                'id' => 5,
                'name' => 'MC-2',
                'address' => 'Sector22',
                'parent_id' => 3,
            ],
            [
                'id' => 6,
                'name' => 'MC-2',
                'address' => 'Sector22',
                'parent_id' => 3,
            ]
        ]);
    }
}
