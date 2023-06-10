<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo($permission)
    {
        $this->permissions()->attach($permission);
    }

    public function hasPermissionTo($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}
