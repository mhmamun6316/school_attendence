<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendence extends BaseModel
{
    use HasFactory;

    protected $fillable = ['student_id','device_id','organization_id','arrived_time'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','student_id');
    }
}
