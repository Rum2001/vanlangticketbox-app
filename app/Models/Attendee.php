<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'event_name',
        'status',
        'verify_code',
        'checkin_at',   
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'checkin_at',
    ];
}
