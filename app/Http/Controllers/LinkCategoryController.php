<?php

namespace App\Http\Controllers;

use App\Models\LinkCategory;
use App\Http\Requests\StoreLinkCategoryRequest;
use App\Http\Requests\UpdateLinkCategoryRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class LinkCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:link-categories.view', only: ['index']),
            new Middleware('permission:link-categories.edit', only: ['edit', 'update']),
            new Middleware('permission:link-categories.add', only: ['add', 'store']),
            new Middleware('permission:link-categories.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        $query = LinkCategory::query();

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
        $countCategories = $query->paginate($request->rows);

        return response()->json($countCategories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLinkCategoryRequest $request)
    {
        //
        LinkCategory::create([
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LinkCategory $linkCategory)
    {
        //
        return LinkCategory::where('id', $linkCategory)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLinkCategoryRequest $request, LinkCategory $linkCategory)
    {
        //
        $query = LinkCategory::find($request->id);
        $query->update([
            'name' => $request->name,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LinkCategory $linkCategory)
    {
        //
        $linkCategory->delete();
        return response()->json(null, 204);
    }
}
