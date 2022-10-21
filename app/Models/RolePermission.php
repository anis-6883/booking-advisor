<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    public static function hasPermissionTo($role_id, $permission_id) 
    {
        $exists = RolePermission::where('role_id', $role_id)
            ->where('permission_id', $permission_id)
            ->exists();
        if($exists)
            return true;
        else
            return false;
    }

    public static function roleHasPermission($role_id, $permissions) 
    {
        $hasPermission = true;

        foreach ($permissions as $permission) {
            if(!self::hasPermissionTo($role_id, $permission->id)){
                $hasPermission = false;
            }
        }
        return $hasPermission;
    }
}
