<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'default_currency',
        'preferences',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'preferences'       => 'array',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function conversionHistories()
    {
        return $this->hasMany(ConversionHistory::class);
    }

    public function rateAlerts()
    {
        return $this->hasMany(RateAlert::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}