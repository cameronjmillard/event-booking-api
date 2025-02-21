<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $perPage = is_numeric($perPage) && $perPage > 0 ? (int) $perPage : 10;

        $bookings = Booking::paginate($perPage);

        return response()->json($bookings);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return response()->json($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }
}
