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

    public function packageLogs()
    {
        return $this->hasMany(StudentPackageLog::class);
    }

    public function package()
    {
        return $this->belongsToMany(Package::class, 'student_package_logs')
            ->wherePivot('status', 1);
    }

    public function logs()
    {
        return $this->hasMany(StudentPackageLog::class, 'student_id');
    }
}
