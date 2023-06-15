<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'phone',
        'avatar',
        'masv',
        'role', 
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function attendee()
    {
        return $this->hasMany(Attendee::class);
    }

    public function eventManager()
    {
        return $this->hasMany(EventManager::class);
    }
}
