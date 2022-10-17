<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::create(['name' => 'super_admin']);

        $permissions = [
            // hotel permission
            'hotels.index',
            'hotels.create',
            'hotels.edit',
            'hotels.destroy',
            // admin permission
            'admins.index',
            'admins.create',
            'admins.edit',
            'admins.destroy',
        ];

        foreach($permissions as $permission) {
            $superAdminRole->givePermissionTo(Permission::create(['name' => $permission]));
        }   

        // $role->givePermissionTo($permission);
    }
}
