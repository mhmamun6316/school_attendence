<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterByOrganization;

class BaseModel extends Model
{
    use FilterByOrganization;

}
