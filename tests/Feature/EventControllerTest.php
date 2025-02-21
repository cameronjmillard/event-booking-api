<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_an_event()
    {
        $data = [
            'name' => 'Sample Event',
            'description' => 'Event Description',
            'location' => 'Sample Location',
            'date' => now(),
            'capacity' => 100,
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'Sample Event',
            'location' => 'Sample Location',
        ]);
    }

    /** @test */
    public function can_update_an_event()
    {
        $event = Event::factory()->create();

        $data = ['name' => 'Updated Event Name'];
        $response = $this->putJson("/api/events/{$event->id}", $data);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Updated Event Name',
        ]);
    }

    /** @test */
    public function can_delete_an_event()
    {
        $event = Event::factory()->create();

        $response = $this->deleteJson("/api/events/{$event->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    /** @test */
    public function can_book_an_event()
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
    public function prevents_overbooking_for_an_event()
    {
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee1 = Attendee::factory()->create();
        $attendee2 = Attendee::factory()->create();
        $attendee3 = Attendee::factory()->create();

        $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee1->id]);
        $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee2->id]);

        // Attempt to overbook
        $response = $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee3->id]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Event is fully booked']);
    }

    /** @test */
    public function prevents_doublebooking_for_an_event()
    {
        $event = Event::factory()->create(['capacity' => 3]);
        $attendee1 = Attendee::factory()->create();
        $attendee2 = Attendee::factory()->create();

        $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee1->id]);
        $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee2->id]);

        // Attempt to double booking
        $response = $this->postJson("/api/events/{$event->id}/book", ['attendee_id' => $attendee1->id]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Attendee has already booked this event']);
    }

    public function test_default_events_pagination()
    {
        Event::factory()->count(15)->create();
        $response = $this->getJson('/api/events');
        
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
        Event::factory()->count(25)->create();

        // Request the events API with a per_page of 5
        $response = $this->getJson('/api/events?per_page=5');

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
        Event::factory()->count(25)->create();

        // Request the events API with a per_page of 5 for page 2
        $response = $this->getJson('/api/events?page=2');

        // Assert the response is successful
        $response->assertStatus(200);

        $this->assertCount(10, $response->json('data'));

        $this->assertEquals(25, $response->json('total'));
        $this->assertEquals(2, $response->json('current_page'));
        $this->assertEquals(10, $response->json('per_page'));
    }
}
