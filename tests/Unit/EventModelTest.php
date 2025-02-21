<?php 

namespace Tests\Unit;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_available_spots()
    {
        // Given an event with a capacity of 100
        $event = Event::factory()->create([
            'capacity' => 5,
        ]);

        for ($i = 0; $i < 4; $i++) {
            $attendee = Attendee::factory()->create();
            Booking::factory()->create([
                'event_id' => $event->id,
                'attendee_id' => $attendee->id
            ]);
        }

        $this->assertEquals(true, $event->hasAvailableSpots());
    }

    /** @test */
    public function no_available_spots()
    {
        // Given an event with a capacity of 100
        $event = Event::factory()->create([
            'capacity' => 1,
        ]);

        $attendee = Attendee::factory()->create();
        Booking::factory()->create([
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);

        $this->assertEquals(false, $event->hasAvailableSpots());
    }
}
