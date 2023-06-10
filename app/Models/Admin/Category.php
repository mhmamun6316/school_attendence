<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    CONST PACKAGES = [
        'Messenger' => 1,
        'SMS' => 2,
        'WhatsApp' => 3,
        'Telegram' => 4,
        'Slack' => 5
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }

}
