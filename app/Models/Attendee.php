<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'bookings');
    }

    /**
     * Ensure that the attendee's data is valid before saving.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function booted()
    {
        static::saving(function ($attendee) {
            $validator = \Validator::make(
                $attendee->attributesToArray(),
                [
                    'email' => 'required|email|unique:attendees,email',
                ]
            );

            if ($validator->fails()) {
                return false; // Prevent the save and return false
            }
        });
    }
}