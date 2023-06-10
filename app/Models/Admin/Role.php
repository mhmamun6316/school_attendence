<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
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
