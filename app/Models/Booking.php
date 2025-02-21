<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'attendee_id',
    ];

    /**
     * Get the event that owns the booking.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the attendee who made the booking.
     */
    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }
}
