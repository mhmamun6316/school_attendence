<?php

namespace App\Models;

use App\Models\Admin\Organization;
use App\Models\Admin\Role;
use App\Traits\FilterByOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, FilterByOrganization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'phone',
        'address',
        'dob',
        'gender'
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

    const ROLES = [
        'super_admin' => 2
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isSuperAdmin()
    {
        return $this->roles()->where('id', User::ROLES['super_admin'])->exists();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignRole($role)
    {
        $this->roles()->attach($role);
    }

    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();

        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $permissionNames = $permissions->pluck('name')->toArray();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return count(array_diff($permissionNames, $rolePermissions)) === 0;
    }

    public function hasPermission($permission)
    {
        $roles = $this->roles()->with('permissions')->get();

        foreach ($roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }

        return false;
    }

}
