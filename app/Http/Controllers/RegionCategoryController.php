<?php

namespace App\Http\Controllers;

use App\Models\RegionCategory;
use App\Http\Requests\StoreRegionCategoryRequest;
use App\Http\Requests\UpdateRegionCategoryRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RegionCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:region-categories.view', only: ['index']),
            new Middleware('permission:region-categories.edit', only: ['edit', 'update']),
            new Middleware('permission:region-categories.add', only: ['add', 'store']),
            new Middleware('permission:region-categories.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = RegionCategory::query();

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
        $countRegionCategories = $query->paginate($request->rows);

        return response()->json($countRegionCategories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RegionCategory $regionCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionCategoryRequest $request, RegionCategory $regionCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegionCategory $regionCategory)
    {
        //
    }
}
