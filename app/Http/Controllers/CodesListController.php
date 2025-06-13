<?php

namespace App\Http\Controllers;

use App\Models\CodesList;
use App\Http\Requests\StoreCodesListRequest;
use App\Http\Requests\UpdateCodesListRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CodesListController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:codes-lists.view', only: ['index']),
            new Middleware('permission:codes-lists.edit', only: ['edit', 'update']),
            new Middleware('permission:codes-lists.add', only: ['add', 'store']),
            new Middleware('permission:codes-lists.delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = CodesList::query();

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
        $countCodesList = $query->paginate($request->rows);

        return response()->json($countCodesList);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCodesListRequest $request)
    {
        //
        CodesList::create([
            'data_entry'   => $request->data_entry,
            'label'        => $request->label,
            'meaning'      => $request->meaning,
            'codes_list'   => $request->codes_list,
            'is_active'     => $request->is_active,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CodesList $codesList)
    {
        //
        return CodesList::where('id', $codesList)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCodesListRequest $request, CodesList $codesList)
    {
        //
        $query = CodesList::find($request->id);
        $query->update([
            'data_entry'   => $request->data_entry,
            'label'        => $request->label,
            'meaning'      => $request->meaning,
            'codes_list'   => $request->codes_list,
            'is_active'     => $request->is_active,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CodesList $codesList)
    {
        //
        $codesList->delete();
        return response()->json(null, 204);
    }
}
