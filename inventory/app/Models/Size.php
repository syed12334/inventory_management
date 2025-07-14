<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Size extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sizes';

    protected $primaryKey = 's_id';

    protected $fillable = [
        'sname',
        'status',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->size_uuid = (string) Str::uuid(); 
        });
    }
    
}
