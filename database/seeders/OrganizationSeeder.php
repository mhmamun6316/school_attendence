<?php

namespace Database\Seeders;

use App\Models\Admin\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = [
            ['id' => 1, 'name' => 'Krc Tech','parent_id' => 0,'address' => 'Dhaka'],
        ];

        foreach ($organizations as $organization) {
            Organization::updateOrCreate(['id' => $organization['id']], $organization);
        }
    }
}
