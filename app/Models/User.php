<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
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