<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Http\Requests\StoreAirlineRequest;
use App\Http\Requests\UpdateAirlineRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AirlineController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:users.view', only: ['index']),
            new Middleware('permission:airlines.edit', only: ['edit', 'update']),
            new Middleware('permission:airlines.add', only: ['add', 'store']),
            new Middleware('permission:airlines.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = Airline::with('country','airline_type');

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
                if (!empty($value['value'])) { 
                    if (str_contains($filter, '.')) {
                        // Split the filter into relation and field (e.g., 'region_type.region_type')
                        [$relation, $field] = explode('.', $filter);
                        $query->whereHas($relation, function ($q) use ($field, $value) {
                            $q->where($field, 'like', '%' . $value['value'] . '%');
                        });
                    } else {                    
                    $query->where($filter, 'like', '%' . $value['value'] . '%');
                    }
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
        $countAirlines = $query->paginate($request->rows);

        return response()->json($countAirlines);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAirlineRequest $request)
    {
        //
        Airline::create([
            'airline_name' => $request->airline_name,
            'iata'         => $request->iata,
            'icao'         => $request->icao,
            'callsign'     => $request->callsign,
            'alies'        => $request->alies,
            'sort_order'   => $request->sort_order,
            'airline_type_id' => $request->airline_type_id,
            'country_id'   => $request->country_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Airline $airline)
    {
        //
        return Airline::where('id', $airline)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAirlineRequest $request, Airline $airline)
    {
        //
        $query = Airline::find($request->id);
        $query->update([
            'airline_name' => $request->airline_name,
            'iata'         => $request->iata,
            'icao'         => $request->icao,
            'callsign'     => $request->callsign,
            'alies'        => $request->alies,
            'sort_order'   => $request->sort_order,
            'airline_type_id' => $request->airline_type_id,
            'country_id'   => $request->country_id,
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Airline $airline)
    {
        //
        $airline->delete();
        return response()->json(null, 204);
    }
}
