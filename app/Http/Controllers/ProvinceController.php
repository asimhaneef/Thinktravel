<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Http\Requests\StoreProvinceRequest;
use App\Http\Requests\UpdateProvinceRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ProvinceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:provinces.view', only: ['index']),
            new Middleware('permission:provinces.edit', only: ['edit', 'update']),
            new Middleware('permission:provinces.add', only: ['add', 'store']),
            new Middleware('permission:provinces.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        $query = Province::with('country');

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
                if (!empty($value['value'])) {
                    if ($filter == 'country.country') {
                        $query->whereHas('country', function ($q) use ($value) {
                            $q->where('country', 'like', '%' . $value['value'] . '%');
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
            if ($request['sortField'] == 'country.country') {
                $query->whereHas('country', function ($q) use ($order) {
                    $q->orderBy('country', $order);
                });
            } else {
                $query->orderBy($request['sortField'], $order);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $countCountries = $query->paginate($request->rows);

        return response()->json($countCountries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProvinceRequest $request)
    {
        // If the incoming request has `is_default` set, unset any existing default
        if ($request->is_default) {
            Province::where('is_default', 1)->update(['is_default' => 0]);
        }

        Province::create([
            'province_name' => $request->province_name,
            'province_code' => $request->province_code,
            'country_id' => $request->country_id,
            'is_default' => $request->is_default ? 1 : 0,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        return Province::where('id', $province)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinceRequest $request, Province $province)
    {
        // If the incoming request has `is_default` set, unset any existing default
        if ($request->is_default) {
            Province::where('is_default', 1)->update(['is_default' => 0]);
        }
        $query = Province::find($request->id);
        $query->update([
            'province_name' => $request->province_name,
            'province_code' => $request->province_code,
            'country_id' => $request->country_id,
            'is_default' => $request->is_default ? 1 : 0,
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        $province->delete();
        return response()->json(null, 204);
    }
}
