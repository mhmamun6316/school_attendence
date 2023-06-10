<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name','group_name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function permissionGroups()
    {
        return static::select('group_name')->distinct()->get()->pluck('group_name');
    }
}
