<?php

namespace App\Http\Controllers;

use App\Models\AirlineType;
use App\Http\Requests\StoreAirlineTypeRequest;
use App\Http\Requests\UpdateAirlineTypeRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AirlineTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:airline-types.view', only: ['index']),
            new Middleware('permission:airline-types.edit', only: ['edit', 'update']),
            new Middleware('permission:airline-types.add', only: ['add', 'store']),
            new Middleware('permission:airline-types.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = AirlineType::query();

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
        $countAirlineTypes = $query->paginate($request->rows);

        return response()->json($countAirlineTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAirlineTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AirlineType $airlineType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAirlineTypeRequest $request, AirlineType $airlineType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AirlineType $airlineType)
    {
        //
    }
}
