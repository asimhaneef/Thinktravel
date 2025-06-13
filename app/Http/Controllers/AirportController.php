<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Http\Requests\StoreAirportRequest;
use App\Http\Requests\UpdateAirportRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AirportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:airports.view', only: ['index']),
            new Middleware('permission:airports.edit', only: ['edit', 'update']),
            new Middleware('permission:airports.add', only: ['add', 'store']),
            new Middleware('permission:airports.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = Airport::with('city.province.country', 'city.country');

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
                if (!empty($value['value'])) {                    
                    $query->where($filter, 'like', '%' . $value['value'] . '%');
                }
            }
        }

        // Sorting
        if ($request->has('sortField') && $request->has('sortOrder')) {
            $order = $request['sortOrder'] == 1 ? 'asc' : 'desc';            
            $query->orderBy($request['sortField'], $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $countAirports = $query->paginate($request->rows);

        return response()->json($countAirports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAirportRequest $request)
    {
        //

        Airport::create([
            'airport_name' => $request->airport_name,
            'iata'         => $request->iata,
            'icao'         => $request->icao,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'altitude'     => $request->altitude,
            'timezone'     => $request->timezone,
            'dst'          => $request->dst,
            'city_id'      => $request->city_id,
            'is_active'    => $request->is_active ? 1 : 0,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Airport $airport)
    {
        //
        return Airport::where('id', $airport)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAirportRequest $request, Airport $airport)
    {
        //
        $query = Airport::find($request->id);
        $query->update([
            'airport_name' => $request->airport_name,
            'iata'         => $request->iata,
            'icao'         => $request->icao,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'altitude'     => $request->altitude,
            'timezone'     => $request->timezone,
            'dst'          => $request->dst,
            'city_id'      => $request->city_id,
            'is_active'    => $request->is_active ? 1 : 0,
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Airport $airport)
    {
        //
        $airport->delete();
        return response()->json(null, 204);
    }
}
