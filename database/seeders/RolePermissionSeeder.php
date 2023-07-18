<?php

namespace Database\Seeders;

use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view',
                ]
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    'role.create',
                    'role.view',
                    'role.edit',
                    'role.delete',
                ]
            ],
            [
                'group_name' => 'organization',
                'permissions' => [
                    'organization.create',
                    'organization.view',
                    'organization.edit',
                    'organization.delete',
                ]
            ],
            [
                'group_name' => 'admin',
                'permissions' => [
                    'admin.create',
                    'admin.view',
                    'admin.edit',
                    'admin.delete',
                ]
            ],
            [
                'group_name' => 'device',
                'permissions' => [
                    'device.create',
                    'device.view',
                    'device.edit',
                    'device.delete',
                ]
            ],
            [
                'group_name' => 'package',
                'permissions' => [
                    'package.create',
                    'package.view',
                    'package.edit',
                    'package.delete',
                ]
            ],
            [
                'group_name' => 'student',
                'permissions' => [
                    'student.create',
                    'student.view',
                    'student.edit',
                    'student.delete',
                    'student.history',
                    'student.deactivate',
                ]
            ],
            [
                'group_name' => 'attendance',
                'permissions' => [
                    'attendance.view',
                    'attendance.filter',
                ]
            ],
            [
                'group_name' => 'bill',
                'permissions' => [
                    'bill.view',
                ]
            ],
        ];

        $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                // Create Permission
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup]);
                $roleSuperAdmin->givePermissionTo($permission);
            }
        }

        // Assign super admin role permission to superadmin user
        $user = User::where('email', 'mamun@gmail.com')->first();
        if ($user) {
            $user->assignRole($roleSuperAdmin);
        }
    }
}
