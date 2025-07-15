<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unit extends Model
{
    protected $fillable = [
        'unit_uuid', 'name', 'code',
        'conversion_rate', 'is_base',
        'updated_by',
    ];

    protected static function booted(): void
    {
        static::creating(function ($unit) {
            $unit->unit_uuid ??= (string) Str::uuid();
        });
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
