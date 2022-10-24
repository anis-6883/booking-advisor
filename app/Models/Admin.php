<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public static function isAdmin($role_id){
        $role = Role::find($role_id);
        if($role->name != 'Admin'){
            return false;
        }
        return true;
    }

    public static function hasPermissionTo($premission_name, $role_id)
    {
        $permission = Permission::where('name', $premission_name)->first();

        $exists = RolePermission::where('role_id', $role_id)
            ->where('permission_id', $permission->id)
            ->exists();
            
        if($exists)
            return true;
        else
            return false;
    }
}
