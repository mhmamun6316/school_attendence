<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
}
