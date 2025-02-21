<?php 

namespace Tests\Feature;

use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_an_attendee()
    {
        $data = ['name' => 'John Doe', 'email' => 'johndoe@example.com'];

        $response = $this->postJson('/api/attendees', $data);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com'
        ]);
    }

    /** @test */
    public function can_update_an_attendee()
    {
        $attendee = Attendee::factory()->create();

        $data = ['name' => 'Greg', 'email' => 'test@test.com'];
        $response = $this->putJson("/api/attendees/{$attendee->id}", $data);

        $response->assertStatus(200);
        $response->assertJson(['name' => 'Greg', 'email' => 'test@test.com']);
    }

    /** @test */
    public function can_delete_an_attendee()
    {
        $attendee = Attendee::factory()->create();

        $response = $this->deleteJson("/api/attendees/{$attendee->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('attendees', ['id' => $attendee->id]);
    }

    public function test_default_events_pagination()
    {
        Attendee::factory()->count(15)->create();
        $response = $this->getJson('/api/attendees');
        
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
        Attendee::factory()->count(25)->create();

        // Request the events API with a per_page of 5
        $response = $this->getJson('/api/attendees?per_page=5');

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
        Attendee::factory()->count(25)->create();

        // Request the events API with a per_page of 5 for page 2
        $response = $this->getJson('/api/attendees?page=2');

        // Assert the response is successful
        $response->assertStatus(200);

        $this->assertCount(10, $response->json('data'));

        $this->assertEquals(25, $response->json('total'));
        $this->assertEquals(2, $response->json('current_page'));
        $this->assertEquals(10, $response->json('per_page'));
    }
}
