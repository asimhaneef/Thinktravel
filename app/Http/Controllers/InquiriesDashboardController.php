<?php

namespace App\Http\Controllers;

use App\Models\InquiriesDashboard;
use App\Models\Booking;
use App\Models\User;
use App\Models\Airline;
use App\Http\Requests\StoreInquiriesDashboardRequest;
use App\Http\Requests\UpdateInquiriesDashboardRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InquiriesDashboardController extends Controller
{
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
    public function store(StoreInquiriesDashboardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InquiriesDashboard $inquiriesDashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInquiriesDashboardRequest $request, InquiriesDashboard $inquiriesDashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InquiriesDashboard $inquiriesDashboard)
    {
        //
    }
    public function InquiryCounts($from, $to)
    {
        //
        $startDate = Carbon::parse($from);
        $endDate = Carbon::parse($to);
        $counts = [
            'open' => Booking::where('booking_status', 'Open')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(),
            'cancelled' => Booking::where('booking_status', 'Cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(),
            'completed' => Booking::where('booking_status', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(),
            'in_progress' => Booking::where('booking_status', 'In-Progress')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count()
        ];
    
        return response()->json($counts);
    }

    public function DaterangeInquiryCounts($from, $to)
    {
        $startDate = Carbon::parse($from);
        $endDate = Carbon::parse($to);

        // Generate all dates in the range first
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('j-n');
            $currentDate->addDay();
        }

        // Get all bookings in the date range at once
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            });

        // Initialize counts with zeros
        $countsData = [
            'open' => array_fill(0, count($dates), 0),
            'cancelled' => array_fill(0, count($dates), 0),
            'completed' => array_fill(0, count($dates), 0),
            'in_progress' => array_fill(0, count($dates), 0)
        ];

        // Process each day's bookings
        $currentDate = $startDate->copy();
        $index = 0;
        while ($currentDate <= $endDate) {
            $dateKey = $currentDate->format('Y-m-d');
            
            if (isset($bookings[$dateKey])) {
                foreach ($bookings[$dateKey] as $booking) {
                    $status = strtolower(str_replace('-', '_', $booking->booking_status));
                    if (isset($countsData[$status])) {
                        $countsData[$status][$index]++;
                    }
                }
            }

            $currentDate->addDay();
            $index++;
        }

        return response()->json([
            'dates' => $dates,
            'counts' => $countsData
        ]);
    }

    public function AgentInquiryCounts($agent_id = null, $from = null, $to = null)
    {
        // Validate and parse dates
        $startDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $endDate = $to ? Carbon::parse($to)->endOfDay() : null;
        // Get all active agents if no agent_id specified
        $agents = $agent_id != "null"
            ? User::where('id', $agent_id)->with('role')->get()
            : User::whereHas('role', function($query) {
                $query->where('name', 'Agent');
            })->with('role')->get();

        $agentNames = [];
        $openCounts = [];
        $cancelledCounts = [];
        $inProgressCounts = [];
        $completedCounts = [];

        foreach ($agents as $agent) {
            $agentNames[] = $agent->last_name;
            
            $openCounts[] = Booking::where('agent_name', $agent->id)
                ->where('booking_status', 'Open')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                
            $cancelledCounts[] = Booking::where('agent_name', $agent->id)
                ->where('booking_status', 'Cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                
            $inProgressCounts[] = Booking::where('agent_name', $agent->id)
                ->where('booking_status', 'In-Progress')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                
            $completedCounts[] = Booking::where('agent_name', $agent->id)
                ->where('booking_status', 'Completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        return response()->json([
            'agents' => $agentNames,
            'open' => $openCounts,
            'cancelled' => $cancelledCounts, // Note: Fixed typo from 'cancelled'
            'in_progress' => $inProgressCounts,
            'completed' => $completedCounts
        ]);
    }

    public function RegionInquiryCounts($from, $to)
    {
        // Validate and parse dates
        $startDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $endDate = $to ? Carbon::parse($to)->endOfDay() : null;

        // Base query with cruise destination relationship
        $query = Booking::with('cruisesDestination')
        ->has('cruisesDestination');

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Get booking counts grouped by regions
        $regionData = $query->selectRaw('cruising_location, COUNT(*) as bookings_count')
            ->groupBy('cruising_location')
            ->get()
            ->filter()
            ->map(function ($item) {
                return [
                    'region_name' => $item->cruisesDestination->region ?? 'Unknown Region',
                    'bookings_count' => $item->bookings_count
                ];
            });

        // Separate into two arrays
        $regions = [];
        $counts = [];
        
        foreach ($regionData as $data) {
            $regions[] = $data['region_name'];
            $counts[] = $data['bookings_count'];
        }

        return response()->json([
            'regions' => $regions,
            'counts' => $counts,
            'full_data' => $regionData // Optional: include original data if needed
        ]);
    }
    
    public function AirlineInquiryCounts($airline_id = null, $from = null, $to = null)
    {
        // Validate and parse dates
        $startDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $endDate = $to ? Carbon::parse($to)->endOfDay() : null;
        // Get airlines based on whether a specific airline_id was provided
        $airlinesQuery = $airline_id != "null" && $airline_id != null
            ? Airline::where('id', $airline_id)
            : Airline::query();

        // Get airlines with their booking counts through the BookingFlight relation
        $airlinesWithCounts = $airlinesQuery->withCount([
            'bookingFlights' => function($query) use ($startDate, $endDate) {
                $query->whereHas('booking', function($bookingQuery) use ($startDate, $endDate) {
                    // Add any booking status filters if needed
                    // $bookingQuery->where('booking_status', 'Open');
                    if ($startDate && $endDate) {
                        $bookingQuery->whereBetween('created_at', [$startDate, $endDate]);
                    }
                });
            }
        ])->get();

        // Transform the data into the required format
        $airlineNames = [];
        $bookingCounts = [];

        foreach ($airlinesWithCounts as $airline) {
            $airlineNames[] = $airline->airline_name; 
            $bookingCounts[] = $airline->booking_flights_count;
        }

        return response()->json([
            'airlines' => $airlineNames,
            'count' => $bookingCounts
        ]);
    }
    
    public function CalenderInquiryStats()
    {
        $bookings = Booking::all();
        $groupedEvents = [];

        // Group bookings by date and status
        foreach ($bookings as $booking) {
            $date = $booking->created_at->format('Y-m-d');
            $status = $booking->booking_status;

            if (!isset($groupedEvents[$date][$status])) {
                $groupedEvents[$date][$status] = 0;
            }
            $groupedEvents[$date][$status]++;
        }

        $events = [];
        foreach ($groupedEvents as $date => $statusCounts) {
            foreach ($statusCounts as $status => $count) {
                $color = match ($status) {
                    'Open' => 'lightgreen',
                    'In-Progress' => 'lightblue',
                    'Completed' => 'lightgoldenrodyellow',
                    'Cancelled' => 'lightgrey',
                    default => 'transparent',
                };

                $events[] = [
                    'title' => "<span class='text-black' style='font-size: 10px'>{$status} ({$count})</span>",
                    'start' => $date,
                    'color' => $color,
                    'extendedProps' => [
                        'status' => $status,
                        'count' => $count
                    ]
                ];
            }
        }

        return response()->json($events);
    }
}
