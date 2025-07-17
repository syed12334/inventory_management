<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'user_uuid',
        'name',
        'email',
        'mobile_number',
        'email_verified_at',
        'password',
        'warehouse_store_id',
        'jsontext',
        'profile_image',
        'status',
        'remember_token',
    ];

    /**
     * Automatically generate UUID on creation.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->user_uuid)) {
                $user->user_uuid = (string) Str::uuid();
            }
        });
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
