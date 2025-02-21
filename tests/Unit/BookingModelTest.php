<?php 

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Attendee;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function prevents_duplicate_bookings()
    {
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();

        // Create a booking
        Booking::create(['event_id' => $event->id, 'attendee_id' => $attendee->id]);

        // Assert that the attendee can't book the same event again
        $this->expectException(\Illuminate\Database\QueryException::class);
        Booking::create(['event_id' => $event->id, 'attendee_id' => $attendee->id]);
    }
}
