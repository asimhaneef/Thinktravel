<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RegionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:regions.view', only: ['index']),
            new Middleware('permission:regions.edit', only: ['edit', 'update']),
            new Middleware('permission:regions.add', only: ['add', 'store']),
            new Middleware('permission:regions.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = Region::with('region_category','region_type','parent_region','child_regions');

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
            if ($request['sortField'] == 'region_type.region_type') {
                [$relation, $field] = explode('.', $request['sortField']);
                $query->join(
                    'region_types',
                    'regions.region_type_id', // Adjust this to match your foreign key
                    '=',
                    'region_types.id'
                )->orderBy($field, $order);
            }   else{     
                $query->orderBy($request['sortField'], $order);
            }
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
    public function store(StoreRegionRequest $request)
    {
        //
        Region::create([
            'region'             => $request->region,
            'region_desc'        => $request->region_desc,
            'sort_order'         => $request->sort_order,
            'parent_region_id'   => $request->parent_region_id,
            'region_category_id' => $request->region_category_id,
            'region_type_id'     => $request->region_type_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
        return Region::where('id', $region)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        //
        $query = Region::find($request->id);
        $query->update([
            'region'             => $request->region,
            'region_desc'        => $request->region_desc,
            'sort_order'         => $request->sort_order,
            'parent_region_id'   => $request->parent_region_id,
            'region_category_id' => $request->region_category_id,
            'region_type_id'     => $request->region_type_id
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
        $region->delete();
        return response()->json(null, 204);
    }
}
