<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $perPage = is_numeric($perPage) && $perPage > 0 ? (int) $perPage : 10;

        $events = Event::paginate($perPage);

        return response()->json($events);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'date' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        $event = Event::create($request->all());

        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'location' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'capacity' => 'sometimes|integer|min:1',
        ]);

        $event->update($validated);
        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function bookEvent(Request $request, $eventId)
    {
        $request->validate([
            'attendee_id' => 'required|exists:attendees,id', //attendee exists
        ]);

        $event = Event::findOrFail($eventId);
        $attendee = Attendee::findOrFail($request->attendee_id);

        // Prevent overbooking
        if ($event->bookings()->count() >= $event->capacity) {
            return response()->json(['error' => 'Event is fully booked'], 400);
        }

        // Prevent duplicate booking
        if ($event->bookings()->where('attendee_id', $attendee->id)->exists()) {
            return response()->json(['error' => 'Attendee has already booked this event'], 400);
        }

        $booking = Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);

        return response()->json($booking, 201);
    }
}
