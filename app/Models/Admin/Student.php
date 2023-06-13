<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends BaseModel
{
    use HasFactory;
    protected $fillable = ['name', 'avatar', 'phone', 'email', 'address', 'guardian_phone', 'guardian_email', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    //for all packages for logs
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'student_package')
            ->withPivot('start_date', 'end_date', 'active_status')
            ->withTimestamps();
    }

    //for the latest active package of a student. use first() from the relationship
    public function activePackage()
    {
        return $this->belongsToMany(Package::class, 'student_package')
            ->wherePivot('active_status', true)
            ->withPivot(['active_status', 'start_date', 'end_date']);
    }
}
