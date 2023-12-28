<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
          'Superadmin',
          'Admin',
          'LSL',
          'User',
      ];
        $super_permissions = [
            'saleArea-create',
            'brand-create',
             'role-edit',
             'user-delete',
             'user-list',
             'saleArea-list',
             'brand-list',
        ];
        $admin_permissions = [
            'user-list',
             'account-edit',
             'welcomeData-list',
             'saleArea-list',
             'brand-list',
          ];

        $user_permissions = [
                    'welcomeData-create',
                    'welcomeData-list',
                    'account-edit'
                ];
        $lsl_permissions = [
                    'welcomeData-list',
                    'user-list',
                    'account-edit'
                ];



        foreach ($user_permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        foreach ($super_permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role) {
            $role = Role::create(['name' => $role]);
            if ($role['name'] == 'Superadmin') {
                $permissions = Permission::all();
                $role->syncPermissions($permissions);
            }

            if ($role['name'] == 'Admin') {
                $role->syncPermissions($admin_permissions);
            }

            if ($role['name'] =='User') {
                $role->syncPermissions($user_permissions);
            }
            if ($role['name'] =='LSL') {
                $role->syncPermissions($lsl_permissions);
            }
        }

        $superadmin = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@r2omm.com',
                'password'=>'admin!@#123',
            ],
        ];

        $admin = [
            [
                'name' => 'Admin',
                'email' => 'admin@r2omm.com',
                'password' => 'admin!@#123',
            ],
        ];

        foreach ($superadmin as $su) {
            $user = User::create([
                        'name' => $su['name'],
                        'email' => $su['email'],
                        'password' => bcrypt($su['password']),
                    ]);
            $user->assignRole('Superadmin');
        }

        foreach ($admin as $ad) {
            $user = User::create([
                        'name' => $ad['name'],
                        'email' => $ad['email'],
                        'password' => bcrypt($ad['password']),
                    ]);
            $user->assignRole('Admin');
        }

    }
}
