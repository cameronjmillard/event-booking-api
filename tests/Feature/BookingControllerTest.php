<?php 

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Attendee;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_booking()
    {
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();

        $data = ['attendee_id' => $attendee->id];
        $response = $this->postJson("/api/events/{$event->id}/book", $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_bookings_for_the_same_attendee()
    {
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();

        $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee->id]);

        $response = $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee->id]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Attendee has already booked this event']);
    }

    public function test_default_events_pagination()
    {
        Booking::factory()->count(15)->create();
        $response = $this->getJson('/api/bookings');
        
        $response->assertStatus(200);

        $this->assertCount(10, $response->json('data'));

        $this->assertEquals(15, $response->json('total'));
        $this->assertEquals(1, $response->json('current_page'));
        $this->assertEquals(10, $response->json('per_page'));
    }

    /**
     * Test pagination on events with a different per_page value.
     *
     * @return void
     */
    public function test_events_paginate_with_custom_per_page()
    {
        // Create 25 events
        Booking::factory()->count(25)->create();

        // Request the events API with a per_page of 5
        $response = $this->getJson('/api/bookings?per_page=5');

        $response->assertStatus(200);

        $this->assertCount(5, $response->json('data'));
    }

    /**
     * Test pagination with different page.
     *
     * @return void
     */
    public function test_events_paginate_with_custom_page()
    {
        // Create 25 events
        Booking::factory()->count(25)->create();

        // Request the events API with a per_page of 5 for page 2
        $response = $this->getJson('/api/bookings?page=2');

        // Assert the response is successful
        $response->assertStatus(200);

        $this->assertCount(10, $response->json('data'));

        $this->assertEquals(25, $response->json('total'));
        $this->assertEquals(2, $response->json('current_page'));
        $this->assertEquals(10, $response->json('per_page'));
    }
}
