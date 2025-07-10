<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Category;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'subcategories';
    protected $primaryKey = 'subcategory_id';
    public $timestamps = true;

    protected $fillable = [
        'subcategory_name',
        'slug',
        'status',
        'user_id',
        'subcategory_uuid',
        'category_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->subcategory_uuid)) {
                $model->subcategory_uuid = (string) Str::uuid();
            }
        });
    }

    // 🔗 Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔗 Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
