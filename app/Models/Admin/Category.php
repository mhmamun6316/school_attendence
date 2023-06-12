<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;
    protected $fillable = ['name'];

    CONST CATEGORY = [
        'Messenger' => 1,
        'SMS' => 2,
        'WhatsApp' => 3,
        'Telegram' => 4,
        'Email' => 5
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }

}
