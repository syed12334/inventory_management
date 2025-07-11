<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $table = 'brands';           
    protected $primaryKey = 'brand_id';    
    public $incrementing = true;           
    protected $keyType = 'int';

    protected $fillable = [
        'brand_uuid',
        'user_id',
        'title',
        'brand_img',
        'status',
    ];

    /**
     * Automatically generate a UUID for new records
     */
    protected static function booted()
    {
        static::creating(function ($brand) {
            if (empty($brand->brand_uuid)) {
                $brand->brand_uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Example relationship (if you have a User model)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
