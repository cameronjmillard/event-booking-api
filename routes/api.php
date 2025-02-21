<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/**
 * Attendee Routes
 */
Route::resource('attendees', AttendeeController::class)->except(['create', 'edit']);

Route::resource('events', EventController::class)->except(['create', 'edit']);
Route::post('events/{event}/book', [EventController::class, 'bookEvent']);


Route::resource('bookings', BookingController::class)->except(['create', 'edit']);