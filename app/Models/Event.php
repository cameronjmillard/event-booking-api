<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'location', 'date', 'capacity',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function hasAvailableSpots(): bool
    {
        return $this->bookings()->count() < $this->capacity;
    }

    public function bookable(Attendee $attendee): bool
    {
        return $this->hasAvailableSpots() && !$this->bookings->contains('attendee_id', $attendee->id);
    }
}
