<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogs extends Model
{
    protected $fillable =[
        'id',
        'user_id',
        'from_status',
        'type'
    ];
}
