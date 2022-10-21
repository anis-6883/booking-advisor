<?php

namespace Database\Seeders;

use App\Models\Admin;
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
        $admin = Admin::where('email', 'admin@email.com')->first();
        $superAdminRole = Role::create(['name' => 'admin']);

        $admin->assignRole('admin');

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
