<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        $attendees = Attendee::all();

        foreach ($events as $event) {
            $availableAttendees = $attendees->random($event->capacity);

            foreach ($availableAttendees as $attendee) {
                Booking::create([
                    'event_id' => $event->id,
                    'attendee_id' => $attendee->id,
                ]);
            }
        }
    }
}
