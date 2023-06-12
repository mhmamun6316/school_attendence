<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPackageLog extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'package_id', 'status'];

}
