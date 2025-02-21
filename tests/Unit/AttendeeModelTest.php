<?php 

namespace Tests\Unit;

use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function requires_valid_email_for_creation()
    {
        $attendee = new Attendee([
            'name' => 'John Doe',
            'email' => 'invalid-email',
        ]);

        $this->assertFalse($attendee->save());
    }

    /** @test */
    public function has_valid_email_on_creation()
    {
        $attendee = new Attendee([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        $this->assertTrue($attendee->save());
    }
}
