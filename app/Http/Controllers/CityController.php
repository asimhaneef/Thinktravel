<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:cities.view', only: ['index']),
            new Middleware('permission:cities.edit', only: ['edit', 'update']),
            new Middleware('permission:cities.add', only: ['add', 'store']),
            new Middleware('permission:cities.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = City::with('province', 'country'); 

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
                if (!empty($value['value'])) {
                    if ($filter == 'province.province') {
                        $query->whereHas('province', function ($q) use ($value) {
                            $q->where('province_name', 'like', '%' . $value['value'] . '%');
                        });
                    }
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
            if ($request['sortField'] == 'province.province') {
                $query->whereHas('province', function ($q) use ($order) {
                    $q->orderBy('province_name', $order);
                });
            }
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
        $countCities = $query->paginate($request->rows);

        return response()->json($countCities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request)
    {
        //
        if ($request->is_default) {
            City::where('is_default', 1)->update(['is_default' => 0]);
        }

        City::create([
            'city_name' => $request->city_name,
            'city_code' => $request->city_code ?? null,
            'country_id' => $request->country_id ?? null,
            'province_id' => $request->province_id ?? null,
            'is_default' => $request->is_default ? 1 : 0,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
        return City::where('id', $city)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        //
        if ($request->is_default) {
            City::where('is_default', 1)->update(['is_default' => 0]);
        }
        $query = City::find($request->id);
        $query->update([
            'city_name' => $request->city_name,
            'city_code' => $request->city_code ?? null,
            'country_id' => $request->country_id ?? null,
            'province_id' => $request->province_id ?? null,
            'is_default' => $request->is_default ? 1 : 0,
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
        $city->delete();
        return response()->json(null, 204);
    }
}
