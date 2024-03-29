<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name','description','device_number','is_archived'];

    public function organization()
    {
        return $this->BelongsTO(Organization::class, );
    }
}
