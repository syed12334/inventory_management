<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'category_id';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'slug',
        'status',
        'user_id',
        'category_uuid',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->category_uuid)) {
                $model->category_uuid = (string) Str::uuid();
            }
        });
    }

    // 🔗 Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔗 Relationship with subcategory
    public function SubCategory()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}
