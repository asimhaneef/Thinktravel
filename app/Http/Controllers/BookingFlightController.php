<?php

namespace App\Http\Controllers;

use App\Models\BookingFlight;
use App\Http\Requests\StoreBookingFlightRequest;
use App\Http\Requests\UpdateBookingFlightRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class BookingFlightController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:booking-flights.view', only: ['index']),
            new Middleware('permission:booking-flights.edit', only: ['edit', 'update']),
            new Middleware('permission:booking-flights.add', only: ['add', 'store']),
            new Middleware('permission:booking-flights.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingFlightRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BookingFlight $bookingFlight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingFlightRequest $request, BookingFlight $bookingFlight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookingFlight $bookingFlight)
    {
        //
    }
}
