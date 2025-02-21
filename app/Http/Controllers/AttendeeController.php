<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $perPage = is_numeric($perPage) && $perPage > 0 ? (int) $perPage : 10;

        $attendees = Attendee::paginate($perPage);

        return response()->json($attendees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:attendees,email',
        ]);

        $attendee = Attendee::create($request->all());

        return response()->json($attendee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendee $attendee)
    {
        return response()->json($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendee $attendee)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:attendees,email,' . $attendee->id,
        ]);

        $attendee->update($request->all());

        return response()->json($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendee $attendee)
    {
        $attendee->delete();
        return response()->json(null, 204);
    }
}
