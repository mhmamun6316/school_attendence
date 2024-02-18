<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['id' => 1, 'name' => 'dashboard.view', 'group_name' => 'dashboard'],
            ['id' => 2, 'name' => 'role.create', 'group_name' => 'role'],
            ['id' => 3, 'name' => 'role.view', 'group_name' => 'role'],
            ['id' => 4, 'name' => 'role.edit', 'group_name' => 'role'],
            ['id' => 5, 'name' => 'role.delete', 'group_name' => 'role'],
            ['id' => 6, 'name' => 'organization.create', 'group_name' => 'organization'],
            ['id' => 7, 'name' => 'organization.view', 'group_name' => 'organization'],
            ['id' => 8, 'name' => 'organization.edit', 'group_name' => 'organization'],
            ['id' => 9, 'name' => 'organization.delete', 'group_name' => 'organization'],
            ['id' => 10, 'name' => 'admin.create', 'group_name' => 'admin'],
            ['id' => 11, 'name' => 'admin.view', 'group_name' => 'admin'],
            ['id' => 12, 'name' => 'admin.edit', 'group_name' => 'admin'],
            ['id' => 13, 'name' => 'admin.delete', 'group_name' => 'admin'],
            ['id' => 14, 'name' => 'device.create', 'group_name' => 'device'],
            ['id' => 15, 'name' => 'device.view', 'group_name' => 'device'],
            ['id' => 16, 'name' => 'device.edit', 'group_name' => 'device'],
            ['id' => 17, 'name' => 'device.delete', 'group_name' => 'device'],
            ['id' => 18, 'name' => 'package.create', 'group_name' => 'package'],
            ['id' => 19, 'name' => 'package.view', 'group_name' => 'package'],
            ['id' => 20, 'name' => 'package.edit', 'group_name' => 'package'],
            ['id' => 21, 'name' => 'package.delete', 'group_name' => 'package'],
            ['id' => 22, 'name' => 'student.create', 'group_name' => 'student'],
            ['id' => 23, 'name' => 'student.view', 'group_name' => 'student'],
            ['id' => 24, 'name' => 'student.edit', 'group_name' => 'student'],
            ['id' => 25, 'name' => 'student.delete', 'group_name' => 'student'],
            ['id' => 26, 'name' => 'student.history', 'group_name' => 'student'],
            ['id' => 27, 'name' => 'student.deactivate', 'group_name' => 'student'],
            ['id' => 28, 'name' => 'attendance.view', 'group_name' => 'attendance'],
            ['id' => 29, 'name' => 'attendance.filter', 'group_name' => 'attendance'],
            ['id' => 30, 'name' => 'bill.view', 'group_name' => 'bill']
        ]);
    }
}
