<?php

namespace App\Http\Controllers;

use App\Models\RegionType;
use App\Http\Requests\StoreRegionTypeRequest;
use App\Http\Requests\UpdateRegionTypeRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RegionTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:region-types.view', only: ['index']),
            new Middleware('permission:region-types.edit', only: ['edit', 'update']),
            new Middleware('permission:region-types.add', only: ['add', 'store']),
            new Middleware('permission:region-types.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = RegionType::query();

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
        $countRegionTypes = $query->paginate($request->rows);

        return response()->json($countRegionTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RegionType $regionType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionTypeRequest $request, RegionType $regionType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegionType $regionType)
    {
        //
    }
}
