<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'status'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_package')
            ->withPivot('start_date', 'end_date', 'active_status')
            ->withTimestamps();
    }
}
