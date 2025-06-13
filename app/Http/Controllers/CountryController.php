<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CountryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:countries.view', only: ['index']),
            new Middleware('permission:countries.edit', only: ['edit', 'update']),
            new Middleware('permission:countries.add', only: ['add', 'store']),
            new Middleware('permission:countries.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $request = request();
        $query = Country::query();

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
            if ($request['sortOrder'] == 1) {
                $order = 'asc';
            } else {
                $order = 'desc';
            }
            $query->orderBy($request['sortField'], $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // Pagination - return all records if 'rows' parameter is not specified
        $perPage = $request->rows ?? Country::count();
        return response()->json($query->paginate(
            min($perPage, 1000), // Prevent excessively large pages
            ['*'], // Columns to select
            'page', // Page name
            $request->page ?? 1 // Current page
        ));
        // Pagination
        // $countCountries = $query->paginate($request->rows);

        // return response()->json($countCountries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {
        Country::create([
            'country' => $request->country,
            'is_default' => $request->is_default,
            'country_code' => $request->country_code,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return Country::where('id', $country)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $query = Country::find($request->id);
        $query->update([
            'country' => $request->country,
            'is_default' => $request->is_default,
            'country_code' => $request->country_code,
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return response()->json(null, 204);
    }
}
