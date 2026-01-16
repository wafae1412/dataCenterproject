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
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

 public function isAdmin()
    {
        return $this->role && $this->role->name === 'Admin';
    }
    public function isResponsable()
    {
        return $this->role && $this->role->name === 'Responsable';
    }
    public function isUser()
    {
        return $this->role && $this->role->name === 'Internal';
    }}