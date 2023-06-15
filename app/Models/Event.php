<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'email',
        'locations',
        'locations_id',
        'faculties',
        'faculties_id',
        'scales',
        'scales_id',
        'path',
        'quantity_ticket',
        'start_time',
        'end_time',
        'category_id',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function questionEvents()
    {
        return $this->hasMany(QuestionEvent::class);
    }

    public function imageEvents()
    {
        return $this->hasMany(ImageEvent::class);
    }

    public function feedbackEvents()
    {
        return $this->hasMany(FeedbackEvent::class);
    }

    public function attendee()
    {
        return $this->hasMany(Attendee::class);
    }

    public function eventManager()
    {
        return $this->hasMany(EventManager::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}