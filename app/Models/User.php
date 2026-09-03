<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'status' => 'string',
    ];

    public function isAdmin(): bool
    {
        return $this->hasRole('ADMIN');
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('DOCTOR');
    }

    public function isPharmacist(): bool
    {
        return $this->hasRole('PHARMACIST') || $this->hasRole('PHARMACY_MANAGER');
    }

    public function pharmacy()
    {
        return $this->hasOne(Pharmacy::class, 'owner_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
}