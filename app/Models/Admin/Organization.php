<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id','address'];

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'organization_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'organization_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendence::class, 'organization_id');
    }
}
