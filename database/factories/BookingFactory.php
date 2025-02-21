<?php 

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'event_id' => Event::factory(),
            'attendee_id' => Attendee::factory(),
        ];
    }
}
