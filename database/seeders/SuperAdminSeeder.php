<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = SuperAdmin::where("email", "test-admin@email.com")->first();
        if(is_null($super_admin)){
            $super_admin = new SuperAdmin();
            $super_admin->first_name = "Test";
            $super_admin->last_name = "Admin";
            $super_admin->email = "test-admin@email.com";
            $super_admin->password = Hash::make('password');
            $super_admin->save();
        }
    }
}
